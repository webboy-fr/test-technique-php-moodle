<?php





interface expression_tree_node{
    public function evaluate();
    public function render();
}


class Operations implements expression_tree_node{

    private Array $expression =  [];

    public function __construct($expression){
        //$this->evaluate($expression);
        $this->expression = $expression;
    }


    public function evaluate(){
  
        $result = 0;
        $html = '';
    
        if($this->expression['type'] != 'number'){
    
            
            if ($this->expression['type'] != 'fraction') {
                foreach ($this->expression['children'] as $children) {
                    if ($children['type'] == 'number') {
                        if ($this->expression['type'] == 'add') {
                            $result += $children['value'];
                            $html .= "<div class='{$this->expression['type']}'> {$children['value']} +</div>";
                        } elseif ($this->expression['type'] == 'multiply') {
                            if ($result == 0) {
                                $result = $children['value'];
                            } else {
                                $result = $result * $children['value'];
                            }
                        }
                    } else {
                        if ($this->expression['type'] == 'add') {                        
                            //$result += $this->evaluate($children);
                            $operation = new Operations($children);
                            $result += $operation->evaluate($children);
                        } elseif ($this->expression['type'] == 'multiply') {                        
                            //$result = $result * $this->evaluate($children);
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
    
    
    public function render(){        
        $html = '';
    
        if($this->expression['type'] != 'number'){          
    
                foreach ($this->expression['children'] as $children) {
                
    
                    if ($children['type'] == 'number') {
    
                        if ($this->expression['type'] == 'add') {                       
                            $html .= "<div class='{$this->expression['type']}'> {$children['value']} + </div>";
                        } elseif ($this->expression['type'] == 'multiply') {                       
                            $html .= "<div class='{$this->expression['type']}'> {$children['value']} * </div>";
                        }
                       
                    } else {
    
                        if($children['type'] == 'fraction'){
                            $html .= "<div class='{$children['type']}'> <div>{$children['top']['value']}</div>";                        
                            $html .= "<div>{$children['bottom']['value']}</div>";
                            $html .= "</div>";
                        } else {
                            $operation = new Operations($children);
                            $html .= "(".$operation->render($children).")";
                        }
                    }            
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



$operation = new Operations($expression1);
echo "<div class='operation'>".$operation->render(). " = " . $operation->evaluate() . " </div>";

$operation = new Operations($expression2);
echo "<div class='operation'>".$operation->render(). " = " . $operation->evaluate() . " </div>";

$operation = new Operations($expression3);
echo "<div class='operation'>".$operation->render(). " = " . $operation->evaluate() . " </div>";
?>
</body>
</html>