<?php

function create($class, $attributes = [], $num = null) {
    return factory($class, $num)->create($attributes);
}

function make($class, $attributes = []) {
    return factory($class)->make($attributes);
}

function raw($class, $attributes = []) {
    return factory($class)->raw($attributes);
}