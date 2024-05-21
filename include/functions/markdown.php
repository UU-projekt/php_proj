<?php



function generateHtml($text) {
    $text = strip_tags($text);

    $rules = [
        [ "/#{6}\s?(.+)/", '<h6>${1}</h6>' ],
        [ "/#{5}\s?(.+)/", '<h5>${1}</h5>' ],
        [ "/#{4}\s?(.+)/", '<h4>${1}</h4>' ],
        [ "/#{3}\s?(.+)/", '<h3>${1}</h3>' ],
        [ "/#{2}\s?(.+)/", '<h2>${1}</h2>' ],
        [ "/#{1}\s?(.+)/", '<h1>${1}</h1>' ],
        
        // Matcha blockquotes
        [ "/\> (.+)/", '<blockquote>${1}</blockquote>' ],

        // match fet-text
        [ "/\*\*(.+)\*\*/", '<b>${1}</b>' ],

        // matcha italics
        [ "/\*(.+)\*/", '<i>${1}</i>' ],

        // Matcha länkar
        [ "/\[(.+)\]\((.+)\)/", '<a href="${2}">${1}</a>' ],

        [ "/\- (.+)/", '<ul> <li>${1}</li> </ul>' ],

        // Matcha nya rader
        [ "/^((\r?\n)|(\r\n?))/", '</br>' ],
    ];

    

    foreach($rules as $rule) {
        $text = preg_replace($rule[0], $rule[1], $text);
    }

    return $text;
}