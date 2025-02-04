<?php 

function rupiah($number)
{
    return "Rp " . number_format((float)$number, 0, ',', '.');
}

function tanggal($tanggal){
    return \Carbon\Carbon::parse($tanggal)->format('d F Y');
}