<?php

function evaluate($expression){
    // TODO : add rendering code here

    echo "<pre>EEEEEEEEEEVAL :";
    print_r($expression);

    $result = 0;

    if($expression['type'] != 'number'){

        foreach($expression['children'] as $children){

            if($children['type'] == 'number'){

                if ($expression['type'] == 'add') {
                    $result += $children['value'];
                } else if($expression['type'] == 'multiply'){
                    if($result == 0){
                        $result = $children['value'];
                    } else {
                        $result = $result * $children['value'];
                    }
                }

            } else {   
                
                

                if ($expression['type'] == 'add') {
                    $result += evaluate($children);
                } else if($expression['type'] == 'multiply'){
                    $result = $result * evaluate($children);
                }

                
            }
            echo "RESULT : $result";
        }
    }
    
    

    echo "</pre>";
    return $result;

}

// 100 + 200 + 300
$expression1 = [
    "type" => "add",
    'children'=> [
        [
            "type" => "number",
            "value"=>100
        ],
        [
            "type" => "number",
            "value"=> 200
        ],
        [
            "type" => "number",
            "value"=> 300
        ]
    ]
];

// 100 + 2 * (45 +5)
$expression2 = [
        "type" => "add",
        'children'=> [
                [
                        "type" => "number",
                        "value"=>100
                ],
                [
                        "type" => "multiply",
                        "children" =>[
                                [
                                        "type" => "number",
                                        "value"=>2
                                ],
                                [
                                        "type" => "add",
                                        "children" =>[
                                            [
                                                "type" => "number",
                                                "value"=>5
                                            ],
                                            [
                                                "type" => "number",
                                                "value"=>45
                                            ]
                        ]
                                ]
                        ]
                ]
        ]
];



// 1 + 100 / 1000
$expression3 = [
    "type" => "add",
    'children'=> [
        [
            "type" => "number",
            "value"=>1
        ],
        [
            "type" => "fraction",
            "top"=>
                [
                    "type" => "number",
                    "value"=>100
                ],
            "bottom"=>
                [
                    "type" => "number",
                    "value"=>1000
                ]
        ]
    ]
];

echo "Expression 1 evaluates to: " . evaluate($expression1) . " <br>";

echo "Expression 2 evaluates to: " . evaluate($expression2) . " <br>";

echo "Expression 3 evaluates to: " . evaluate($expression3) . " <br>";
