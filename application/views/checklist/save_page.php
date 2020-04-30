<?php
echo exec('python "' . getcwd() . '/test.py"');
$html2pdf = '"' . getcwd() . '\assets\exec\html2pdf\wkhtmltopdf.exe" ';
echo exec($html2pdf . ' https://localhost/checklist/ "' . getcwd() . '\test.pdf"');
