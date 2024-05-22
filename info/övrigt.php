<?php 
    include "../include/bootstrap.php" ;
    $exampleMarkdown = "
    #### Mindre heading 
    Lorem ipsum **dolor** sit amet, consectetur adipisicing elit. Quos necessitatibus dolores molestias quibusdam harum fugiat corrupti quod quidem provident pariatur officia, voluptatem quo ea similique quisquam quasi molestiae id magni?

    ## Lite större heading 
    > Wow, en [blockquote](https://www.w3schools.com/TAGS/tag_blockquote.asp)! Fräsigt. 
    *- barack obama*
    ";
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Markdown - Redidit</title>
		<link rel="stylesheet" href="/css/index.css">
        <link rel="stylesheet" href="/css/thread.css">
	</head>
	<body class="roboto-regular stack center">
		<?php include "../include/views/_navbar.php" ?>
		
        <div class="layout">
            <?php include "../include/views/_sidebar.php" ?>

            <div class="content stack gap-huge">
            
            <section>
                <h1 class="fredoka-600">Vad är markdown?</h1>
                <p>
                    Markdown är ett s.k <a href="https://sv.wikipedia.org/wiki/M%C3%A4rkspr%C3%A5k">märkspråk</a> som låter användaren snabbt skriva ut hur ett dokument ska se ut i enkel textform.
                    Denna markdown tolkas sedan och görs om till HTML. Detta kan vara ett smidigt mellanting riktig HTML och tråkig text. Det går snabbt att skriva när man väl lärt sig syntaxen.
                </p>
            </section>

            <section>
                <h1 class="fredoka-600">Vad stödjs?</h1>
                <p>
                    Min implementering av markdown är <i>väldigt</i> grundläggande och stödjer därav inte särskilt mycket. 
                    I nuläget finns det stöd för <b>fet text</b>, <i>lutad text</i>, olika storlekar på headings, <a href="https://uu.se">länkar</a>, och blockquotes. </br></br>
                    
                    Du kan använda markdown i kommentarer och i trådar. Tyckte det blev så tråkig stil på trådarna annars :]

                </p>
            </section>

            <section class="stack gap-large">
                <h1 class="fredoka-600">Exempel</h1>
                <h4>Som markdown</h4>
                <code>
                    #### Mindre heading </br>
                    Lorem ipsum **dolor** sit amet, consectetur adipisicing elit. Quos necessitatibus dolores molestias quibusdam harum fugiat corrupti quod quidem provident pariatur officia, voluptatem quo ea similique quisquam quasi molestiae id magni?</br></br>

                    ## Lite större heading </br>
                    > Wow, en [blockquote](https://www.w3schools.com/TAGS/tag_blockquote.asp)! Fräsigt. </br>
                    *- barack obama*
                </code>

                <h4>som HTML</h4>
                <div class="md">
                    <?= generateHtml($exampleMarkdown) ?>
                </div>
            </section>
            </div>
        </div>
	</body>
</html>
