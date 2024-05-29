<?php
$encryption_key = "AITAM2024";
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
  }
  function decrypt_data($data, $key)
 {
  $cipher_algo = "AES-256-CBC";
  // Explode the base64 decoded data
  $decoded_data = base64_decode($data);
  $parts = explode("::", $decoded_data, 2);
  // Check if explode produced at least two parts
  if (count($parts) >= 2) {
      list($encrypted_data, $iv) = $parts;
      // Decrypt the data
      
      return openssl_decrypt($encrypted_data, $cipher_algo, $key, 0, $iv);
  } else {
      // Handle the case where explode didn't produce enough parts
      return "Error: Invalid data format";
  }
}

function selectQuestions($questions, $minRequirements, &$levelCounts)
{
    // Initialize an array to store selected questions
    $selectedQuestions = [];

    // Iterate over minRequirements starting from the level with the highest requirement
    foreach ($minRequirements as $level => $count) {
        // Filter questions containing the current Bloom's level and sort them based on the number of Bloom's levels
        $filteredQuestions = array_filter($questions, function ($question) use ($level) {
            return strpos($question['blooms_level'], $level) !== false;
        });
        usort($filteredQuestions, function ($a, $b) {
            return strlen($b['blooms_level']) - strlen($a['blooms_level']);
        });

        // Iterate over filtered questions and select the ones that meet the requirement
        foreach ($filteredQuestions as $question) {
            // Parse Bloom's levels for the current question
            $bloomLevels = explode(',', strtolower($question['blooms_level']));

       // Check if selecting this question violates any minimum requirements
            $violation = false;
            foreach ($bloomLevels as $level) {
                if ($levelCounts[$level] + 1 > $minRequirements[$level]) {
                    $violation = true;
                    break;
                }
            }

            // If no violation, select the question and update counts
            if (!$violation) {
                // Add the question to the list of selected questions
                $selectedQuestions[] = $question;

                // Update counts for each Bloom's level
                foreach ($bloomLevels as $level) {
                    $levelCounts[$level]--;
                }
            }

            // Stop if the requirement for this level is met
            if ($levelCounts[$level] <= $count) {
                break;
            }
        }
    }

    return $selectedQuestions;
}
?>