<?php

function generarGrupos(int $cantGrup, $grupEsp){
    $grupos =[];
    if ($grupEsp!= null) {
        $grupos = match($cantGrup){
            1=> [],
            2=> ["B"],
            3=> ["B","C"],
            4=> ["B","C","D"],
            5=> ["B","C","D","E"],
            6=> ["B","C","D","E","F"],
            7=> ["B","C","D","E","F","G"],
            8=> ["B","C","D","E","F","G","H"],
            9=> ["B","C","D","E","F","G","H","I"],
            10=>["B","C","D","E","F","G","H","I","J"],
        };
    }else{
        $grupos = match($cantGrup){
            1=> ["A"],
            2=> ["A", "B"],
            3=> ["A", "B","C"],
            4=> ["A", "B","C","D"],
            5=> ["A", "B","C","D","E"],
            6=> ["A", "B","C","D","E","F"],
            7=> ["A", "B","C","D","E","F","G"],
            8=> ["A", "B","C","D","E","F","G","H"],
            9=> ["A", "B","C","D","E","F","G","H","I"],
            10=>["A", "B","C","D","E","F","G","H","I","J"],
        };
    }
    return $grupos;
}

