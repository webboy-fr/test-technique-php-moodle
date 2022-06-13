<?php

interface expression_tree_node
{
    public function evaluate();
    public function render();
}


class Operations implements expression_tree_node
{
    private array $expression =  [];

    public function __construct($expression)
    {
        $this->expression = $expression;
    }


    public function evaluate()
    {
        $result = 0;
        $type = $this->expression['type'];
        
    
        if ($type != 'number') {
            if ($type != 'fraction') {
                foreach ($this->expression['children'] as $children) {
                    if ($children['type'] == 'number') {
                        if ($type == 'add') {
                            $result += $children['value'];
                        } elseif ($type == 'multiply') {
                            if ($result == 0) {
                                $result = $children['value'];
                            } else {
                                $result = $result * $children['value'];
                            }
                        }
                    } else {
                        if ($type == 'add') {
                            //Recursivity
                            $operation = new Operations($children);
                            $result += $operation->evaluate($children);
                        } elseif ($type == 'multiply') {
                            //Recursivity
                            $operation = new Operations($children);
                            $result = $result * $operation->evaluate($children);
                        }
                    }
                }
            } else {
                return $this->expression['top']['value'] / $this->expression['bottom']['value'];
            }
        }
        return $result;
    }
    
    
    public function render()
    {
        $html = '';
        $type = $this->expression['type'];

    
        if ($type != 'number') {
            $i = 0;
            foreach ($this->expression['children'] as $children) {
                if ($children['type'] == 'number') {
                    if ($type == 'add') {
                        $html .= "<div class='{$type}'>{$children['value']}";
                        if (isset($this->expression['children'][$i+1])) { //Write + only if there is a next element
                            $html .= "&nbsp;+&nbsp;"; //Sorry
                        }
                        $html .= "</div>";
                    } elseif ($type == 'multiply') {
                        $html .= "<div class='{$type}'>{$children['value']}";
                        if (isset($this->expression['children'][$i+1])) { //Write * only if there is a next element
                            $html .= "&nbsp;*&nbsp;"; //Sorry
                        }
                        $html .= "</div>";
                    }
                } else {
                    if ($children['type'] == 'fraction') {
                        $html .= "<div class='{$children['type']}'> <div>{$children['top']['value']}</div>";
                        $html .= "<div>{$children['bottom']['value']}</div>";
                        $html .= "</div>";
                    } else {
                        $operation = new Operations($children);
                        $html .= "(".$operation->render($children).")";
                    }
                }
                
                $i++;
            }
        }
        
     
        return $html;
    }
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
    //Sorry for non breakable spaces, I don't have the time... ^^

    $operation = new Operations($expression1);
    echo "<div class='operation'>".$operation->render(). "&nbsp;= " . $operation->evaluate() . " </div>";

    $operation = new Operations($expression2);
    echo "<div class='operation'>".$operation->render(). "&nbsp;= " . $operation->evaluate() . " </div>";

    $operation = new Operations($expression3);
    echo "<div class='operation'>".$operation->render(). "&nbsp;= " . $operation->evaluate() . " </div>";
    ?>
</body>
</html>