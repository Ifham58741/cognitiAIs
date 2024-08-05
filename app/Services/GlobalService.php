<?php

function languagesList2() {
    $fields = \DB::getSchemaBuilder()->getColumnListing('strings');
    $exceptions = ['en','code','created_at','updated_at'];
    $filtered = collect($fields)->filter(function ($value, $key) use($exceptions){
        if (!in_array($value,$exceptions) ) {
            return $value;
        }
    });
    return $filtered->all();
}