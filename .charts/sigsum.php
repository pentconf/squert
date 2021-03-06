<?php

//
//
//      Copyright (C) 2010 Paul Halliday <paul.halliday@gmail.com>
//
//      This program is free software: you can redistribute it and/or modify
//      it under the terms of the GNU General Public License as published by
//      the Free Software Foundation, either version 3 of the License, or
//      (at your option) any later version.
//
//      This program is distributed in the hope that it will be useful,
//      but WITHOUT ANY WARRANTY; without even the implied warranty of
//      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//      GNU General Public License for more details.
//
//      You should have received a copy of the GNU General Public License
//      along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
//

$scc = "SELECT COUNT(signature) as count, signature
        FROM event
        WHERE $when
        GROUP BY signature
        ORDER BY count DESC";

$sccQuery = mysql_query($scc);

$i = $sigsE = 0;

$plot1 = $label1 = $key1 = '';

while ($row = mysql_fetch_row($sccQuery)) {
    $i++;
    $plot1 .= $row[0] . ",";
    $label1 .= "'" . $row[1] . "'" . ",";
    if (strlen($row[1]) > 100) {
        $name = substr($row[1], 0,100) . "..";
    } else {
        $name = $row[1];
    }
    $key1 .= "'" . $name . " (" . $row[0] . ")" . "'" . ",";
    if ($i == 10) { break; }
}

if ($i == 0) { $sigsE = 1; }

$colours = "'#4E4E9E', '#ECE64F', '#3E9AC0', '#4A4557', '#A817DF', '#14870D', '#925E72', '#B3B88C', '#5F4A4F', '#E3ECE8', '#1F5BCD', '#30858C', '#6DC787', '#8FF045', '#22FFBE'";

// Chart Logic

echo "<canvas id=\"sigs\" width=\"960\" height=\"350\">[No canvas support]</canvas>";

echo "\r<script>";

if ($sigsE != 1) {

    echo "
          function createSigs () {
            var ct = new RGraph.Pie('sigs', [$plot1]);
            ct.Set('chart.centerx', 130);
            ct.Set('chart.tooltips', [$label1]);
            ct.Set('chart.tooltips.effect', 'fade');
            ct.Set('chart.tooltips.event', 'onmousemove');
            ct.Set('chart.text.size', 8);
            ct.Set('chart.key', [$key1]);
            ct.Set('chart.key.background', 'rgba(255,255,255,0.3)');
            ct.Set('chart.key.position.y', 70);
            ct.Set('chart.key.position.x', 300);
            ct.Set('chart.colors', [$colours]);
            ct.Set('chart.strokestyle', '#ffffff');
            ct.Set('chart.align', 'left');
            ct.Set('chart.radius', 120);
            ct.Set('chart.shadow', false);
            ct.Set('chart.variant', 'donut');
            ct.Set('chart.linewidth', 2);
            ct.Draw();
          }

          createSigs();";

} else {

    echo "
          var sigs_canvas = document.getElementById(\"sigs\");
          var sigs_context = sigs_canvas.getContext(\"2d\");
          sigs_context.font = \"14px calibri, trebuchet ms, helvetica\";
          sigs_context.fillStyle = \"#000000\";
          sigs_context.fillText(\"No result for this time period\", 160,175);";
}

echo "</script>";

?>
