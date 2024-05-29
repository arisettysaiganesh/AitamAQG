<?php
function detectBloomsLevels($questionText) {
    $bloomsKeywords = array(
        'l1' => array('recall', 'define', 'identify', 'recognize', 'choose', 'describe', 'is', 'are', 'where', 'which', 'who was', 'why did', 'what is', 'what are', 'when did', 'outline', 'list', 'remember', 'describe','What is','How is','Where is','When did','How did',
    'How would you explain',
    'How would you describe',
    'What do you recall',
    'How would you show',
    'Who (what) were the main',
    'What are three',
    'What is the definition of'),
        'l2' => array('compare', 'contrast', 'clarify', 'differentiate between', 'generalize', 'express', 'infer', 'observe', 'identify', 'describe', 'restate', 'elaborate on', 'what if', 'what is the main idea', 'what can you say about','How would you classify the type of',
    'How would you compare',
    'contrast',
    'How would you rephrase the meaning',
    'What facts or ideas show',
    'What is the main idea of',
    'Which statements support',
    'How can you explain what is meant',
    'What can you say about',
    'Which is the best answer',
    'How would you summarize'),
        'l3' => array('demonstrate', 'present', 'change', 'modify', 'solve', 'use', 'predict', 'construct', 'perform', 'classify', 'apply','How would you use',
    'What examples can you find to',
    'How would you solve',
    'using what you have learned',
    'How would you organize',
    'to show',
    'How would you show your understanding of',
    'What approach would you use to',
    'How would you apply what you learned to develop',
    'What other way would you plan to',
    'What would result if',
    'How can you make use of the facts to',
    'What elements would you choose to change',
    'What facts would you select to show',
    'What questions would you ask'),
        'l4' => array('sort', 'analyze', 'identify', 'examine', 'investigate', 'what', 'assumptions', 'validate', 'ideas', 'explain', 'what is the main idea','What are the parts or features of',
    'How is',
    'related to',
    'Why do you think',
    'What is the theme',
    'What motive is there',
    'What conclusions can you draw',
    'How would you classify',
    'How can you identify the different parts',
    'What evidence can you find',
    'What is the relationship between',
    'How can you make a distinction between',
    'What is the function of',
    'What ideas justify'),
        'l5' => array('evaluate', 'find', 'select', 'decide', 'justify', 'debate', 'judge', 'criteria', 'verify', 'information', 'prioritize',
        'Why do you agree with the actions? The outcomes?',
    'What is your opinion of',
    '(Must explain why)',
    'How would you prove',
    'disprove',
    'How can you assess the value or importance of',
    'What would you recommend',
    'How would you rate or evaluate the',
    'What choice would you have made',
    'How would you prioritize',
    'What details would you use to support the view',
    'Why was it better than'
        ),
        'l6' => array('suggest', 'revise', 'explain the reason', 'generate a plan', 'invent', 'gather facts', 'predict the outcome', 'portray', 'devise a way', 'compile facts', 'elaborate on the reason', 'improve', 'What changes would you make to solve',
    'How would you improve',
    'What would happen if',
    'How can you elaborate on the reason',
    'What alternative can you propose',
    'How can you invent',
    'How would you adapt',
    'to create a different',
    'How could you change',
    'What could be done to minimize',
    'What way would you design',
    'What could be combined to improve',
    'How would you test or formulate a theory for',
    'What would you predict as the outcome',
    'How can a model be constructed that would change',
    'What is an original way for the')
    );

    $detectedLevels = array();

    foreach ($bloomsKeywords as $level => $keywords) {
        foreach ($keywords as $keyword) {
            if (stripos($questionText, $keyword) !== false) {
                $detectedLevels[] = $level;
                break;
            }
        }
    }

    return implode(',', $detectedLevels);
}



// Now $detectedBloomsLevels contains the detected Bloom's levels, you can use it when inserting into the database.
?>
