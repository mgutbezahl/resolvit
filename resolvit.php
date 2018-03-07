<?php
require_once 'src/English.php';

$stemmer = new English();
// Texto de entrada
$txt_input = 'Take this paragraph of text and return an alphabetized list of ALL unique words. A unique word is any form of a word often communicated with essentially the same meaning. For example, fish and fishes could be defined as a unique word by using their stem fish. For each unique word found in this entire paragraph, determine the how many times the word appears in total. Also, provide an analysis of what unique sentence index position or positions the word is found. The following words should not be included in your analysis or result set: "a", "the", "and", "of", "in", "be", "also" and "as". Your final result MUST be displayed in a readable console output in the same format as the JSON sample object shown below.';
//Exclusiones
$exclude = array('a', 'the', 'and', 'of', 'in', 'be', 'also', 'as');
//Caracteres a limpiar
$clean_special = array('"', ',');
//Salida de la operacion
$output = array();
//Limpieza de caracteres especiales, y pasaje minuscula del texto
$txt_input = str_replace($clean_special, '', strtolower($txt_input));
//Separacion en oraciones
$sentences = explode('.', $txt_input);
//ciclado de cada oracion
foreach ($sentences as $idx_sentence => $sentence) {
   //Separacion de paralabra
   $words = explode(' ', $sentence);
   //Ciclado de palabras
   foreach ($words as $idx_word => $word) {
      //Salteo de las exclusiones, si no esta vacio
      if (!empty($word) && !in_array($word, $exclude)){
         //Obtencion de la raiz de la palabra y la uso para compararla a futuro
         $stem = $stemmer->stem($word);
         //Si existe le sumo en uno las ocurrencias
         if (array_key_exists($stem, $output)){
            $output[$stem]['total-occurrences']++;
         } else {
         //Si no existe agrego el elemento a la salida
            $output[$stem]['word'] = $word;
            $output[$stem]['total-occurrences'] = 1;
            $output[$stem]['sentence-indexes'] = array();
         }
         //Si esa palabra aparece mas de una vez en la misma oracion, no la agrego
         if (!in_array($idx_sentence, $output[$stem]['sentence-indexes']))
            array_push($output[$stem]['sentence-indexes'], $idx_sentence);
      }
   }
}
//Ordenado del resultado
sort($output);
//impresion de la salida
echo json_encode( array('results' => array_values($output) ) );
