<?php

function evaluate($expression){
    $result = 0;
    $type = $expression['type'];

    if($type != 'number'){        
        if ($type != 'fraction') {
            foreach ($expression['children'] as $children) {
                if ($children['type'] == 'number') { //Simple operation
                    if ($type == 'add') {
                        $result += $children['value'];
                    } elseif ($type == 'multiply') {
                        //No multplication by zero + ternary
                        $result = ($result == 0) ? $children['value'] :  $result * $children['value'];                         
                    }
                } else { //Must dive deeper (recursivity)
                    if ($type == 'add') {                        
                        $result += evaluate($children);
                    } elseif ($type == 'multiply') {                        
                        $result = $result * evaluate($children);
                    }
                }            
            }
        } else {
            //Fraction looks simpler, so I didn't have implement recursivity for it :) (but I could...)
            return $expression['top']['value'] / $expression['bottom']['value'];
        }
    }   
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
