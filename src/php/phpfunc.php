<?php
function createModal($table, $modalid)
{

    $HTMLstring = '<div class="modal fade" id="' . $modalid . '" tabindex="-1" role="dialog" aria-labelledby="renewItemsModalTitle" aria-hidden="true">' .
        '<div class="modal-dialog modal-dialog-centered" role="document">' .
        '<div class="modal-content">' .
        '<div class="modal-body">';

    if (!isset($table[0])) {
        $HTMLstring .= "Impossibile stampare i dati!";
    } else {
        $HTMLstring .= '<table class="table">';

        //Create table headers
        $HTMLstring .= '<thead><tr>';
        foreach ($table[0] as $field => $value) {
            $HTMLstring .= '<th scope="col">' . $field . '<th>';
        }
        $HTMLstring .= '</tr></thead>';

        //Create table body
        $HTMLstring .= '<tbody>';
        foreach ($table as $index => $value) {
            $HTMLstring .= '<tr>';
            foreach ($value as $field => $content) {
                $HTMLstring .= '<td>' . $content . '<td>';
            }
            $HTMLstring .= '</tr>';
        }
        $HTMLstring .= '</tbody>';

        $HTMLstring .= '</table>';
    }
    $HTMLstring .= '</div>' .

        '</div>' .
        '</div>' .
        '</div>';
    return $HTMLstring;
}

function createTableRow($id,$row){
    return "
    <tr>
    <th scope='row'>".$id."</th>
    <td>".$row['loc_desc']."</td>
    </tr>
    ";
}
