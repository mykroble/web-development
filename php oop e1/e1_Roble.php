<?php
    class Car {
        private $brand, $color, $speed;
        function __construct($brand, $color)
        {
            $this->brand = $brand;
            $this->color = $color;
            $this->speed = 0;
        }
       
        public function accelerate($increase){
            $this->speed +=  $increase;
        }
        public function brake($decrease){
            $this->speed -= $decrease;
            if ($this->speed < 0){
                $this->speed = 0;
            }
        }
        public function getDetails(){
            return "Brand: "  .$this->brand. ", Color: " .$this->color. ", Speed: ".$this->speed. " km/h";
         
        }
    }

$mycar = new Car("Honda", "Blue");
$mycar->accelerate(70);
$mycar->brake(20);
echo $mycar->getDetails();
?>