<?php

// Define minimum requirements for each Bloom's level
static $minRequirements = [
    'l1' => 1,
    'l2' => 5,
    'l3' => 5,
    'l4' => 4,
    'l5' => 1,
    'l6' => 5
  ];
  
  // Sort minRequirements in descending order of values
  arsort($minRequirements);
   // Initialize counts for each Bloom's level
   $levelCounts = array_fill_keys(array_keys($minRequirements), 0);
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