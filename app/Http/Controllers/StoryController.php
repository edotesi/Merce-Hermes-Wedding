<?php

namespace App\Http\Controllers;

class StoryController extends Controller
{
    public function index()
    {
        // Añadir imagen de encabezado
        $headerImage = 'images/story/header.jpeg';

        $photos = [
            'images/story/MH1.jpeg',
            'images/story/MH2.jpeg',
            'images/story/MH3.jpeg',
            'images/story/MH4.jpeg',
            'images/story/MH5.jpeg',
            'images/story/MH6.jpeg',
            'images/story/MH7.jpeg',
            'images/story/MH8.jpeg',
            'images/story/MH9.jpeg',
            'images/story/MH10.jpeg',
            'images/story/MH11.jpeg',
            'images/story/MH12.jpeg',
            'images/story/MH13.jpeg',
            'images/story/MH14.jpeg',
            'images/story/MH15.jpeg',
        ];

        return view('story', compact('headerImage', 'photos'));
    }
}