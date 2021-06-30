<?php
if ($module == 'Итоговая ведомость') {

    $text .= "
<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width='95%'
 style='width:95.44%;margin-left:.75pt;border-collapse:collapse;border:none;
 mso-border-alt:solid windowtext 1.5pt;mso-border-insideh:1.5pt solid windowtext;
 mso-border-insidev:1.5pt solid windowtext'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:28.3pt'>
  <td width='4%' style='width:4.56%;border:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=caaieiaie1 style='page-break-after:auto'><b style='mso-bidi-font-weight:
  normal'>№<o:p></o:p></b></p>
  <p class=MsoNormal style='line-height:115%'><b style='mso-bidi-font-weight:
  normal'><span style='font-size:5.0pt;line-height:115%'>&nbsp;&nbsp;&nbsp;&nbsp;П/П</span></b><b
  style='mso-bidi-font-weight:normal'><span style='font-size:14.0pt;line-height:
  115%;color:black'><o:p></o:p></span></b></p>
  </td>
  <td width='53%' style='width:53.32%;border:solid windowtext 1.5pt;border-left:
  none;mso-border-left-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:115%'><b
  style='mso-bidi-font-weight:normal'>Ф.И.О.</b><b style='mso-bidi-font-weight:
  normal'><span style='font-size:14.0pt;line-height:115%;color:black'><o:p></o:p></span></b></p>
  </td>
  <td width='21%' style='width:21.06%;border:solid windowtext 1.5pt;border-left:
  none;mso-border-left-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=MsoNormal align=center style='text-align:center'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:11.0pt;mso-bidi-font-size:
  10.0pt'>Оценка<o:p></o:p></span></b></p>
  </td>
  <td width='21%' style='width:21.06%;border:solid windowtext 1.5pt;border-left:
  none;mso-border-left-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:115%'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:11.0pt;mso-bidi-font-size:
  10.0pt;line-height:115%'>Подпись преподавателя</span></b><b style='mso-bidi-font-weight:
  normal'><span style='font-size:14.0pt;line-height:115%;color:black'><o:p></o:p></span></b></p>
  </td>
 </tr>";

    for ($i = 0; $i < count($student); $i++) {

        $text .= "<tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes;height:18.45pt'>
  <td width='4%' style='width:4.56%;border:solid windowtext 1.5pt;border-top:
  none;mso-border-top-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:18.45pt'>
  <p class=MsoNormal style='margin-left:2.85pt;line-height:115%'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:11.0pt;line-height:
  115%;color:black'>ном<o:p></o:p></span></b></p>
  </td>
  <td width='53%' valign=top style='width:53.32%;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.5pt;border-right:solid windowtext 1.5pt;
  mso-border-top-alt:solid windowtext 1.5pt;mso-border-left-alt:solid windowtext 1.5pt;
  padding:.75pt .75pt .75pt .75pt;height:18.45pt'>
  <p class=MsoNormal align=center style='text-align:center'><span class=SpellE><span
  style='font-size:12.0pt'>$student[$i]</span></span><span style='font-size:12.0pt'><o:p></o:p></span></p>
  </td>
  <td width='21%' style='width:21.06%;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.5pt;border-right:solid windowtext 1.5pt;
  mso-border-top-alt:solid windowtext 1.5pt;mso-border-left-alt:solid windowtext 1.5pt;
  padding:.75pt .75pt .75pt .75pt;height:18.45pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:115%'><span
  style='font-size:14.0pt;line-height:115%;color:black'>$mark[$i]<o:p></o:p></span></p>
  </td>
  <td width='21%' style='width:21.06%;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.5pt;border-right:solid windowtext 1.5pt;
  mso-border-top-alt:solid windowtext 1.5pt;mso-border-left-alt:solid windowtext 1.5pt;
  padding:.75pt .75pt .75pt .75pt;height:18.45pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:115%'><span
  style='font-size:14.0pt;line-height:115%;color:black'><o:p></o:p></span></p>
  </td>
 </tr>";
    }
    $text .= "
</table>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal><span style='font-size:12.0pt'><o:p>&nbsp;</o:p></span></p>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=643
 style='width:482.0pt;margin-left:-1.7pt;border-collapse:collapse;mso-padding-alt:
 0cm 5.4pt 0cm 5.4pt'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;
  height:23.85pt'>
  <td width=416 style='width:311.9pt;padding:0cm 5.4pt 0cm 5.4pt;height:23.85pt'>
  <p class=MsoNormal style='margin-right:-239.35pt'><span style='font-size:
  12.0pt'>Администратор образовательного</span><span style='font-size:14.0pt'><span
  style='mso-spacerun:yes'>  </span><o:p></o:p></span></p>
  <p class=MsoNormal style='margin-right:-239.35pt'><span style='font-size:
  12.0pt'>центра цифровой лицей НИУ &quot;БелГУ&quot;<o:p></o:p></span></p>
  </td>
  <td width=227 style='width:6.0cm;padding:0cm 5.4pt 0cm 5.4pt;height:23.85pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:12.0pt'><span
  style='mso-spacerun:yes'>                           </span>/А.А. Кокорев/</span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal><span style='font-size:12.0pt'><o:p>&nbsp;</o:p></span></p>

</div>

</body>

</html>
";    
}
else {
    $text .= "
    <table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 width='99%'
 style='width:99.84%;margin-left:.75pt;border-collapse:collapse;border:none;
 mso-border-alt:solid windowtext 1.5pt;mso-border-insideh:1.5pt solid windowtext;
 mso-border-insidev:1.5pt solid windowtext'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:28.3pt'>
  <td width='3%' style='width:3.78%;border:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=caaieiaie1 style='page-break-after:auto'><b style='mso-bidi-font-weight:
  normal'>№<o:p></o:p></b></p>
  <p class=MsoNormal style='line-height:115%'><b style='mso-bidi-font-weight:
  normal'><span style='font-size:5.0pt;line-height:115%'>&nbsp;&nbsp;&nbsp;&nbsp;П/П</span></b><b
  style='mso-bidi-font-weight:normal'><span style='font-size:14.0pt;line-height:
  115%;color:black'><o:p></o:p></span></b></p>
  </td>
  <td width='44%' style='width:44.04%;border:solid windowtext 1.5pt;border-left:
  none;mso-border-left-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:115%'><b
  style='mso-bidi-font-weight:normal'>Ф.И.О.</b><b style='mso-bidi-font-weight:
  normal'><span style='font-size:14.0pt;line-height:115%;color:black'><o:p></o:p></span></b></p>
  </td>
  <td width='17%' style='width:17.4%;border:solid windowtext 1.5pt;border-left:
  none;mso-border-left-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=MsoNormal align=center style='text-align:center'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:11.0pt;mso-bidi-font-size:
  10.0pt'>Оценка<o:p></o:p></span></b></p>
  </td>
  <td width='17%' style='width:17.4%;border:solid windowtext 1.5pt;border-left:
  none;mso-border-left-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:115%'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:11.0pt;mso-bidi-font-size:
  10.0pt;line-height:115%'>Дата </span></b><b style='mso-bidi-font-weight:normal'><span
  style='font-size:14.0pt;line-height:115%;color:black'><o:p></o:p></span></b></p>
  </td>
  <td width='17%' valign=top style='width:17.38%;border:solid windowtext 1.5pt;
  border-left:none;mso-border-left-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:28.3pt'>
  <p class=MsoNormal align=center style='text-align:center;line-height:115%'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:11.0pt;mso-bidi-font-size:
  10.0pt;line-height:115%'>Подпись преподавателя<o:p></o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes;height:18.45pt'>
  <td width='3%' style='width:3.78%;border:solid windowtext 1.5pt;border-top:
  none;mso-border-top-alt:solid windowtext 1.5pt;padding:.75pt .75pt .75pt .75pt;
  height:18.45pt'>
  <p class=MsoNormal style='margin-left:18.0pt;text-indent:-15.15pt;line-height:
  115%;mso-list:l2 level1 lfo4;tab-stops:list 0cm'><![if !supportLists]><b
  style='mso-bidi-font-weight:normal'><span style='font-size:11.0pt;line-height:
  115%;color:black'><span style='mso-list:Ignore'>1.<span style='font:7.0pt 'Times New Roman''>&nbsp;&nbsp;&nbsp;&nbsp;
  </span></span></span></b><![endif]><b style='mso-bidi-font-weight:normal'><span
  style='font-size:11.0pt;line-height:115%;color:black'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width='44%' valign=top style='width:44.04%;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.5pt;border-right:solid windowtext 1.5pt;
  mso-border-top-alt:solid windowtext 1.5pt;mso-border-left-alt:solid windowtext 1.5pt;
  padding:.75pt .75pt .75pt .75pt;height:18.45pt'>
  <p class=MsoNormal><span style='font-size:12.0pt'>ФИО<o:p></o:p></span></p>
  </td>
  <td width='17%' style='width:17.4%;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.5pt;border-right:solid windowtext 1.5pt;
  mso-border-top-alt:solid windowtext 1.5pt;mso-border-left-alt:solid windowtext 1.5pt;
  padding:.75pt .75pt .75pt .75pt;height:18.45pt'>
  <p class=MsoNormal style='line-height:115%'><span style='font-size:14.0pt;
  line-height:115%;color:black'>оценка<o:p></o:p></span></p>
  </td>
  <td width='17%' style='width:17.4%;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.5pt;border-right:solid windowtext 1.5pt;
  mso-border-top-alt:solid windowtext 1.5pt;mso-border-left-alt:solid windowtext 1.5pt;
  padding:.75pt .75pt .75pt .75pt;height:18.45pt'>
  <p class=MsoNormal style='line-height:115%'><span style='font-size:14.0pt;
  line-height:115%;color:black'>Дата экзамена<o:p></o:p></span></p>
  </td>
  <td width='17%' valign=top style='width:17.38%;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.5pt;border-right:solid windowtext 1.5pt;
  mso-border-top-alt:solid windowtext 1.5pt;mso-border-left-alt:solid windowtext 1.5pt;
  padding:.75pt .75pt .75pt .75pt;height:18.45pt'>
  <p class=MsoNormal style='line-height:115%'><span style='font-size:14.0pt;
  line-height:115%;color:black'><o:p></o:p></span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal style='margin-top:6.0pt'><span style='color:white;
mso-color-alt:windowtext'>Всего в </span><span class=GramE>группе<span
style='mso-spacerun:yes'>  </span><u><span style='background:yellow;mso-highlight:
yellow'>23</span></u></span></p>

<p class=MsoNormal>Явилось _____<span
style='mso-spacerun:yes'>                                         </span></p>

<p class=MsoNormal>Не явилось______<span
style='mso-spacerun:yes'>                                                              
</span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'>Председатель
аттестационной комиссии<span style='mso-spacerun:yes'>       
</span>_____________<span style='mso-spacerun:yes'>     </span>В.А. Шаповалов<o:p></o:p></b></p>

<p class=MsoNormal><b style='mso-bidi-font-weight:normal'><o:p>&nbsp;</o:p></b></p>

<p class=MsoNormal><span class=GramE><b style='mso-bidi-font-weight:normal'>Члены<span
style='mso-spacerun:yes'>  </span>аттестационной</b></span><b style='mso-bidi-font-weight:
normal'> комиссии:<o:p></o:p></b></p>

<p class=MsoNormal style='margin-right:-239.35pt'>_____________/А.А. Кокорев /,
<span style='font-size:9.0pt'>администратор образовательного<span
style='mso-spacerun:yes'>  </span><o:p></o:p></span></p>

<p class=MsoNormal style='mso-hyphenate:none;tab-stops:0cm 54.0pt'><span
style='font-size:9.0pt'>центра цифровой лицей НИУ &quot;БелГУ&quot;;</span></p>

<p class=MsoNormal style='margin-right:-239.35pt'>_____________/А.Д. Фуников/,
преподаватель <span style='font-size:9.0pt'>образовательного<span
style='mso-spacerun:yes'>  </span><o:p></o:p></span></p>

<p class=MsoNormal style='text-align:justify;tab-stops:14.2pt 54.0pt 2.0cm'><span
style='font-size:9.0pt'>центра цифровой лицей НИУ &quot;БелГУ&quot;</span></p>

</div>

</body>

</html>
    ";
}

print $text;
