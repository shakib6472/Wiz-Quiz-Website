<?php

/* 
Template Name: Admin Settings Page
Description: This template is used to display the admin settings page.
plugin name: Wiz Quiz
Package: Wiz Quiz
Version: 1.0
subpackage: admin/settings
*/
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&family=Lobster&family=Montserrat&family=Lato&family=Oswald&family=Raleway&family=Poppins&family=Merriweather&family=Nunito&family=Ubuntu&family=Playfair+Display&family=PT+Sans&family=PT+Serif&family=Rubik&family=Source+Sans+Pro&family=Quicksand&family=Bitter&family=Arvo&family=Josefin+Sans&family=Mukta&family=Karla&family=Fira+Sans&family=Heebo&family=Anton&family=Work+Sans&family=Zilla+Slab&family=Signika&family=Dosis&family=Bebas+Neue&family=Manrope&family=Cabin&family=Barlow&family=Crimson+Text&family=Inconsolata&family=Asap&family=Dancing+Script&family=Varela+Round&family=Exo+2&family=Mulish&family=Overpass&family=Archivo&family=DM+Sans&family=Slabo+27px&family=Teko&family=Oxygen&family=Roboto+Slab&family=Pacifico&family=Amatic+SC&family=Satisfy&family=Bangers&family=Quattrocento&family=Martel&family=Cormorant+Garamond&family=Nanum+Gothic&family=Abel&family=Alegreya&family=Hind&family=Titillium+Web&family=Rajdhani&family=Noto+Sans&family=Righteous&family=Cairo&family=Maven+Pro&family=Vollkorn&family=Fredericka+the+Great&family=Permanent+Marker&family=Cinzel&family=Arima+Madurai&family=Cookie&family=Pathway+Gothic+One&family=Archivo+Black&family=Kanit&family=Yanone+Kaffeesatz&family=Kaushan+Script&family=Shadows+Into+Light&family=Alegreya+Sans&family=Great+Vibes&family=Eczar&family=Cardo&family=Julius+Sans+One&family=Montserrat+Alternates&family=Francois+One&family=Lora&family=Play&family=Alfa+Slab+One&family=Baloo+2&family=Hammersmith+One&family=Notable&family=Abhaya+Libre&family=Jost&family=Prata&family=Josefin+Slab&family=Sora&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Chakra+Petch&family=Bowlby+One&family=Red+Hat+Display&family=Alatsi&family=Crete+Round&family=Cormorant&family=Caveat&family=Gloria+Hallelujah&family=Zeyada&family=Parisienne&family=Kristi&family=El+Messiri&family=Rochester&family=Scope+One&family=Sanchez&family=Average+Sans&family=Merienda&family=Carter+One&family=Patrick+Hand&family=Itim&family=Bai+Jamjuree&family=Actor&family=Mukta+Vaani&family=Arsenal&family=Yellowtail&family=Norican&family=Lobster+Two&family=Indie+Flower&family=Berkshire+Swash&family=Marcellus&family=Overlock&family=Tangerine&family=Delius&family=Megrim&family=Cantata+One&family=Audiowide&family=Poiret+One&family=Cabin+Sketch&family=Monoton&family=Chewy&family=Arimo&family=Mochiy+Pop+One&family=Asul&family=Sansita+Swashed&family=Secular+One&family=Be+Vietnam+Pro&family=Crimson+Pro&family=Allura&family=Vast+Shadow&family=Gruppo&family=Orbitron&family=Charmonman&family=Fugaz+One&family=Avenir&family=Bree+Serif&family=Inter&family=Lexend&family=Prompt&family=Space+Mono&family=Source+Serif+Pro&family=Spectral&family=Noto+Serif&family=Holtwood+One+SC&family=Gentium+Basic&family=Neuton&family=Frank+Ruhl+Libre&family=Faustina&family=DM+Serif+Display&family=Andika&family=Mate+SC&family=Press+Start+2P&family=Pinyon+Script&family=Poetsen+One&family=Trirong&family=Cutive+Mono&family=Lustria&family=Pridi&family=Rasa&family=Amiri&family=Domine&family=Capriola&family=Bungee+Inline&family=Allerta&family=Tauri&family=Exo&family=Piazzolla&family=Ubuntu+Mono&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Inria+Serif&family=Nunito+Sans&family=Fira+Code&family=Alegreya+SC&family=Josefin+Slab&family=Fira+Sans+Condensed&family=Nunito+Italic&family=Public+Sans&family=Tenor+Sans&family=Dancing+Script&family=Proza+Libre&family=Bungee&family=Spectral+Italic&family=Fjalla+One&family=Cabin+Italic&family=Playfair+Display&family=Asap+Condensed&family=Libre+Franklin&family=Inknut+Antiqua&family=Urbanist&family=Rokkitt&family=Voltaire&family=Baloo+Bhaijaan+2&family=Baloo+Tamma+2&family=Domine+Italic&family=Amiko&family=Recursive&family=Gothic+A1&family=Fira+Sans+Extra+Condensed&family=Markazi+Text&family=Raleway+Italic&family=Roboto+Mono&family=Roboto+Italic&family=Source+Code+Pro&family=Sen+Italic&family=Literata&family=IM+Fell+DW+Pica&family=Fira+Mono+Italic&family=Source+Sans+Pro+Italic&family=Average+Mono&family=Tinos&family=Heebo+Italic&family=Overpass+Italic&family=Epilogue&family=Truculenta&family=Lustria+SC&family=Bree+Serif+Italic&family=Delius+Swash+Caps&family=Economica&family=Abril+Fatface&family=Montserrat+Italic&family=Zilla+Slab+Italic&family=Cormorant+Infant&family=Spectral+SC&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Aleo+Italic&family=Alata&family=Staatliches&family=Catamaran&family=Grandstander&family=Doppio+One&family=Kaisei+Opti&family=Lily+Script+One&family=Padauk&family=Jaldi&family=Pontano+Sans&family=IM+Fell+English&family=Geo&family=Average&family=ABeeZee&family=Fjord+One&family=Arvo+Italic&family=Asul+Italic&family=Vidaloka&family=Kaisei+Tokumin&family=Recursive&family=Yrsa&family=Stint+Ultra+Condensed&family=Ruda&family=Anek+Devanagari&family=Amaranth&family=Miriam+Libre&family=Average+Sans&family=Kaisei+HarunoUmi&family=Unna&family=Pontano+Sans&family=Carattere&family=Fira+Sans+Italic&family=Lexend+Deca&family=Lekton&family=DM+Mono&family=Coustard&family=IM+Fell+DW+Pica&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&family=PT+Sans+Caption&family=Chewy&family=Gruppo&family=Orbitron&family=Dancing+Script&family=Raleway+Dots&family=Press+Start+2P&family=Comic+Neue&family=Fira+Sans+Condensed+ExtraBold&family=Inter+Tight&family=Overlock+SC&family=Stint+Ultra+Condensed&family=Gentium+Plus&family=Vollkorn+Italic&family=Balthazar&family=Podkova+ExtraBold&family=Jaldi&family=Mohave&family=Atkinson+Hyperlegible&family=Zen+Kurenaido&family=Gilda+Display&family=Libre+Caslon+Text&family=IM+Fell+English+SC&display=swap" rel="stylesheet">



<div class="wiz_container header_container">
    <h1>Settings</h1>
    <p>Change Fonts here</p>
</div>
<div class="wiz_container">
    <div class="font-selector-container">
        <h2 class="font-selector-title">Font Family Selector</h2>
        <div id="fontSelector"></div>
        <button id="saveFontsButton" class="save-button">Save Fonts</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-toast-plugin@1.3.2/dist/jquery.toast.min.js"></script>

<script>
    $(document).ready(function() {
        const tags = ["h1", "h2", "h3", "h4", "h5", "h6", "p", "span", "a", "button", "label", "input", "select", "textarea"];
        const fonts = [
            "Roboto", "Open Sans", "Lobster", "Montserrat", "Lato", "Oswald", "Raleway", "Poppins", "Merriweather", "Nunito", "Ubuntu", "Playfair Display", "PT Sans", "PT Serif", "Rubik", "Source Sans Pro", "Quicksand", "Bitter", "Arvo", "Josefin Sans", "Mukta", "Karla", "Fira Sans", "Heebo", "Anton", "Work Sans", "Zilla Slab", "Signika", "Dosis", "Bebas Neue", "Manrope", "Cabin", "Barlow", "Crimson Text", "Inconsolata", "Asap", "Dancing Script", "Varela Round", "Exo 2", "Mulish", "Overpass", "Archivo", "DM Sans", "Slabo 27px", "Teko", "Oxygen", "Roboto Slab", "Pacifico", "Amatic SC", "Satisfy", "Bangers", "Quattrocento", "Martel", "Cormorant Garamond", "Nanum Gothic", "Abel", "Alegreya", "Hind", "Titillium Web", "Rajdhani", "Noto Sans", "Righteous", "Cairo", "Maven Pro", "Vollkorn", "Fredericka the Great", "Permanent Marker", "Cinzel", "Arima Madurai", "Cookie", "Pathway Gothic One", "Archivo Black", "Kanit", "Yanone Kaffeesatz", "Kaushan Script", "Shadows Into Light", "Alegreya Sans", "Great Vibes", "Eczar", "Cardo", "Julius Sans One", "Montserrat Alternates", "Francois One", "Lora", "Play", "Alfa Slab One", "Baloo 2", "Hammersmith One", "Notable", "Abhaya Libre", "Jost", "Prata", "Josefin Slab", "Sora", "Chakra Petch", "Bowlby One", "Red Hat Display", "Alatsi", "Crete Round", "Cormorant", "Caveat", "Gloria Hallelujah", "Zeyada", "Parisienne", "Kristi", "El Messiri", "Rochester", "Scope One", "Sanchez", "Average Sans", "Merienda", "Carter One", "Patrick Hand", "Itim", "Bai Jamjuree", "Actor", "Mukta Vaani", "Arsenal", "Yellowtail", "Norican", "Lobster Two", "Indie Flower", "Berkshire Swash", "Marcellus", "Overlock", "Tangerine", "Delius", "Megrim", "Cantata One", "Audiowide", "Poiret One", "Cabin Sketch", "Monoton", "Chewy", "Arimo", "Mochiy Pop One", "Asul", "Sansita Swashed", "Secular One", "Be Vietnam Pro", "Crimson Pro", "Allura", "Vast Shadow", "Gruppo", "Orbitron", "Charmonman", "Fugaz One", "Avenir", "Bree Serif", "Inter", "Lexend", "Prompt", "Space Mono", "Source Serif Pro", "Spectral", "Noto Serif", "Holtwood One SC", "Gentium Basic", "Neuton", "Frank Ruhl Libre", "Faustina", "DM Serif Display", "Arima", "Andika", "Mate SC", "Press Start 2P", "Pinyon Script", "Poetsen One", "Trirong", "Cutive Mono", "Lustria", "Pridi", "Rasa", "Amiri", "Domine", "Capriola", "Bungee Inline", "Allerta", "Tauri", "Exo", "Piazzolla", "Ubuntu Mono", "Libre Baskerville", "Lora Italic", "Cormorant SC", "Atkinson Hyperlegible", "IBM Plex Sans", "IBM Plex Mono", "Nunito Sans", "Urbanist", "Epilogue", "Syne", "Karla Italic", "Lexend Deca", "DM Mono", "Fira Code", "Alegreya SC", "Azeret Mono", "Andada Pro", "Space Grotesk", "Bungee", "Josefin Slab", "Fjalla One", "Caveat Brush", "Inria Serif", "Public Sans", "Tenor Sans", "Fira Sans Condensed", "Piazzolla", "Arvo Italic", "Lustria Italic", "Libre Franklin", "Dancing Script", "Cinzel Decorative", "Proza Libre", "Spectral SC", "Gentium Plus", "Rokkitt", "ABeeZee", "Dela Gothic One", "Volkhov", "Changa", "Noto Sans KR", "Noto Serif JP", "Crimson Pro Italic", "Asap Condensed", "Commissioner", "Nanum Pen Script", "Roboto Mono", "Baloo Bhaijaan 2", "Baloo Tamma 2", "Libre Bodoni", "Inknut Antiqua", "Zen Kurenaido", "Source Code Pro", "Yeseva One", "Source Serif 4", "Amiko", "Spectral Italic", "Unna", "Miriam Libre", "Overlock SC", "Pontano Sans", "Jaldi", "Taviraj", "Tinos", "Antic Slab", "Delius Swash Caps", "Average", "Didact Gothic", "Nanum Myeongjo", "Aleo", "Merriweather Italic", "Truculenta", "Vidaloka", "Shantell Sans", "Secular One", "Noto Sans SC", "Inter Tight", "Amaranth", "Manrope", "Old Standard TT", "Ruda", "Lekton", "Yrsa", "Arsenal Italic", "Fjord One", "Bellefair", "Quintessential", "Domine Italic", "Julius Sans One", "Literata", "Anek Devanagari", "IM Fell DW Pica", "Cormorant Infant", "Domine Bold", "Signika Negative", "Martian Mono", "Droid Sans Mono", "Big Shoulders Display", "Cormorant Italic", "IM Fell English", "Oldenburg", "Belleza", "Geo", "Economica", "Kurale", "Press Start 2P", "Recursive", "Big Shoulders Text", "Bree Serif Italic", "Cormorant Unicase", "Voltaire", "Bentham", "Staatliches", "Poly", "Heebo Italic", "Padauk", "Chivo Mono", "Grandstander", "Alike", "Noto Sans Thai", "Raleway Italic", "Aleo Italic", "Fira Sans Extra Condensed", "Gothic A1", "Catamaran", "GFS Neohellenic", "Lustria SC", "Fira Mono Italic", "Questrial", "Manjari", "Alata", "Kufam", "Lora SC", "Stint Ultra Condensed", "IBM Plex Serif", "Sen Italic", "Arvo Bold", "Poppins SemiBold", "Doppio One", "Encode Sans", "DM Sans Italic", "Lily Script One", "Cabin Italic", "Federo", "Ubuntu Italic", "Markazi Text", "Rufina", "Kaisei Opti", "Average Mono", "Nunito Italic", "Mukta Mahee", "Carattere", "Fira Sans Italic", "Lexend Tera", "Coustard", "Merriweather Sans", "Gilda Display", "Asul Italic", "Cormorant Upright", "Cormorant Garamond Italic"
        ];
        const selectedFonts = {
            h1: "Roboto",
            h2: "Open Sans",
            h3: "Lobster",
            h4: "Montserrat",
            h5: "Poppins",
            h6: "Merriweather",
            p: "Nunito"
        };


        const fontSelectorContainer = $('#fontSelector');

        tags.forEach(tag => {
            const row = $('<div>').addClass('font-selector-row');
            const column = $('<div>').addClass('font-selector-column');

            const label = $('<label>')
                .addClass('font-selector-label')
                .text(tag.toUpperCase() + ':')
                .attr('for', `${tag}-font`);

            const select = $('<select>')
                .attr({
                    id: `${tag}-font`,
                    name: `${tag}`, // Useful for form submission or backend processing
                    class: 'font-select dropdown', // Adding a class for additional styling or JS hooks
                    'data-tag': tag, // Custom data attribute to store the associated tag
                }).on('change', function() {
                    $(`${tag}`).css('font-family', $(this).val());
                });


            // Iterate through fonts and add options
            fonts.forEach(font => {
                const option = $('<option>')
                    .val(font)
                    .text(font)
                    .css('font-family', font);

                // Check if the current font is the pre-selected font for this tag
                if (font === selectedFonts[tag]) {
                    option.prop('selected', true); // Mark this option as selected
                }

                select.append(option);
            });

            const preview = $('<div>')
                .addClass('font-preview ' + tag)
                .text(`This is a ${tag.toUpperCase()} tag preview.`)
                .css('font-family', fonts[0]);

            select.on('change', function() {
                preview.css('font-family', $(this).val());
            });

            column.append(label).append(select);
            row.append(column).append(preview);
            fontSelectorContainer.append(row);
        });
        // Initialize Select2 for all dynamically added select elements
        $('select').select2({
            placeholder: 'Select a font',
            allowClear: true,
        });


        $('#saveFontsButton').on('click', function() {
            const selectedValues = {};

            tags.forEach(tag => {
                console.log("Processing tag: " + tag);

                // Ensure Select2 is initialized and get the selected value
                const selectedFont = $(`#${tag}-font`).val();
                if (selectedFont) {
                    selectedValues[tag] = selectedFont;
                } else {
                    console.error(`No font selected for ${tag}`);
                }
            });

            // Send the data to the server via AJAX
            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php') ?>', // WordPress AJAX URL provided via wp_localize_script
                data: {
                    action: 'save_font_settings',
                    fonts: selectedValues
                },
                dataType: 'json',
                success: function(response) {
                    $.toast({
                        heading: 'Font Settings Saved',
                        text: 'The font settings have been successfully saved.',
                        showHideTransition: 'slide',
                        icon: 'success',
                        position: 'bottom-right',
                        hideAfter: 5000,
                        stack: 5,
                        loaderBg: '#002664'
                    });

                },
                error: function(xhr, textStatus, errorThrown) {
                    // Handle error
                    console.error('Error:', errorThrown);
                }
            });
        });


    });
</script>