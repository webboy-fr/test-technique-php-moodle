<?php

function evaluate($expression){
  
    $result = 0;
    $html = '';

    if($expression['type'] != 'number'){

        
        if ($expression['type'] != 'fraction') {
            foreach ($expression['children'] as $children) {
                if ($children['type'] == 'number') {
                    if ($expression['type'] == 'add') {
                        $result += $children['value'];
                        $html .= "<div class='{$expression['type']}'> {$children['value']} +</div>";
                    } elseif ($expression['type'] == 'multiply') {
                        if ($result == 0) {
                            $result = $children['value'];
                        } else {
                            $result = $result * $children['value'];
                        }
                    }
                } else {
                    if ($expression['type'] == 'add') {                        
                        $result += evaluate($children);
                    } elseif ($expression['type'] == 'multiply') {                        
                        $result = $result * evaluate($children);
                    }
                }
            
            }
        } else {
            return $expression['top']['value'] / $expression['bottom']['value'];
        }
    }   

 
    return $result;
  
}


function render($expression){
    $html = '';

    if($expression['type'] != 'number'){          

            foreach ($expression['children'] as $children) {
            

                if ($children['type'] == 'number') {

                    if ($expression['type'] == 'add') {                       
                        $html .= "<div class='{$expression['type']}'> {$children['value']} + </div>";
                    } elseif ($expression['type'] == 'multiply') {                       
                        $html .= "<div class='{$expression['type']}'> {$children['value']} * </div>";
                    }
                   
                } else {

                    if($children['type'] == 'fraction'){
                        $html .= "<div class='{$children['type']}'> <div>{$children['top']['value']}</div>";                        
                        $html .= "<div>{$children['bottom']['value']}</div>";
                        $html .= "</div>";
                    } else {
                        $html .= "(".render($children).")";
                    }
                }            
            }      
    }       
    return $html;
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



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        .operation{
            display:flex;
            align-items: center;

        }
        .operation div{
            margin:5px;
        }

 
.fraction{
    display:flex;
    flex-direction: column;
    text-align: center;
}
.fraction div:first-child{
    border-bottom:solid 1px black;
}
    </style>
</head>
<body>
    <?php 

echo "<div class='operation'>".render($expression1). " = " . evaluate($expression1) . " </div>";
echo "<div class='operation'>".render($expression2). " = " . evaluate($expression2) . " </div>";
echo "<div class='operation'>".render($expression3). " = " . evaluate($expression3) . " </div>";
?>
</body>
</html>