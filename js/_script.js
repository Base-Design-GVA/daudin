let clientX, clientY;
let daudinCenterLogoWidth;

window.gwrf;

(function ($) {
    $(document).ready(function () {

        daudinCenterLogoWidth = document.querySelector('.mouse-follow-effect svg').getBoundingClientRect().width;

        initCustomCursor()
        initPageTransition()
        initMenu()
        initAll()
        initDaudinEffect()
        placeTitle()

        ajustElementOnScroll()

        // reset them all !!
        $(window).bind('orientationchange', function (event) {

        })

        $(window).on('resize', function (event) {
            // reload functions
            //daudin_load_sliders()
            initDaudinEffect()
        })

        // add listener to track the current mouse position
        document.addEventListener('mousemove', e => {
            clientX = e.clientX
            clientY = e.clientY
        })

        // pour éviter le scroll au mobile quand on utilise des vh
        window.addEventListener('resize', setVH)
        setVH();

    })
})(jQuery)

function initAll () {
    //placer ici les fonctions d'initialisations qui doivent s'initialiser à chaque page (rappelé après chaque transition
    AOS.init({})
    initPos()
    // initPaddingTop();
    setSmoothScrollAnchor()
    activateDaudinEffect()
    initBrochures()
    initFaqServices()
    initNosEquipes()
    initProgressionBar()
    initFilters()
    initSliderBien()
    initMapAnnonce()
    initActiveLink()
    setMaxValueDaudinEffect()
    swat_load_range_filters()

    //console.log('INITIALISATION DONE !')
}

function setSmoothScrollAnchor() {
    document.querySelectorAll('a[href^="#"]:not([href="#footer-contact"])').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const link = document.querySelector(decodeURI(this.getAttribute('href')));
            if(link.offsetTop) {
                e.preventDefault();
                window.scrollTo({
                    top: link.offsetTop - 180,
                    behavior: 'smooth'
                })
            }

        });
    });
}


let vh;

function setVH () {
    vh = window.innerHeight / 100
    document.querySelector(':root').style
        .setProperty('--vh', vh + 'px')
}

function initPos () {
    try {
        const hash = decodeURI(window.location.hash)

        //on regarde si l'url a une ancre
        if (hash) {
            const hashElement = document.querySelector(hash)
            if(hashElement) {
                //on scroll jusqu'a elle
                window.scrollTo({
                    top: hashElement.offsetTop - 170,
                })
            }
        } else {
            //on se remet en haut
            window.scrollTo({
                top: 0,
            })
        }
    } catch (e) {
        console.error(e)
    }


}

function initCustomCursor () {
    const cursor = document.querySelector('.custom_cursor')

    let isClick = false

    window.addEventListener('mousedown', () => {
        isClick = true
    })

    window.addEventListener('mouseup', () => {
        isClick = false
    })

    const params = {
        offsetX: -20,
        offsetY: -20,
        colorClick: cursor.dataset.clicColor,
        initialColor: cursor.dataset.normalColor,
    }

    if (cursor) {
        // transform the innerCursor to the current mouse position
        // use requestAnimationFrame() for smooth performance
        const followMouse = () => {
            cursor.style.transform = `translate(${clientX + params.offsetX}px, ${clientY + params.offsetY}px)`

            if (isClick) {
                cursor.style.backgroundColor = params.colorClick;
                cursor.style.mixBlendMode = 'normal';
            } else {
                cursor.style.backgroundColor = params.initialColor;
                cursor.style.mixBlendMode = 'multiply';
            }
            requestAnimationFrame(followMouse)
        }
        requestAnimationFrame(followMouse)
    }
}

function initActiveLink () {

    const linkLieuVie = document.querySelector('#link-lieu-vie')
    //on vérifie si cela fait partie de lieux de vie (= page de contenu + liste de bien + bien seul)
    if (document.querySelector('.part-of-lieu-vie')) {
        linkLieuVie.classList.add('active-domain')
    } else {
        linkLieuVie.classList.remove('active-domain')
    }

    if (document.body.classList.contains('home')) return

    //on met aux liens qui pointent vers la page active la classe "inactive"
    document.querySelectorAll('a').forEach(a => {
        ////console.log("a", a, window.location.href)

        if (document.body.classList.contains('menu-open')) {
            a.classList.remove('active')
            a.classList.remove('inactive')
            a.classList.remove('active-domain')
            return
        }

        if (window.location.href.startsWith(a.href) && !a.href.endsWith('#')) {
            ////console.log("oui", a, window.location.href)
            a.classList.add('active')
        } else {
            a.classList.remove('active')
        }
    })

}

function initMapAnnonce () {
    const mapContainer = document.querySelector('#map')
    if (!mapContainer) return

    let mapInit = false

    const addesse = mapContainer.dataset.address

    if (addesse) {
        //on récupère les coordonnées de l'adresse via open street map
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + addesse).then(data => data.json())
            .then(data => {
                //si les données ne sont pas bonnes on initialise pas la carte
                if (!data || data.length < 0) return
                //on prend le premier résultat
                const result = data[0]

                //on initialise la map
                const position = { lat: parseFloat(result.lat), lng: parseFloat(result.lon) }

                const opt = {
                    center: position,
                    zoom: 16
                }

                if (mapContainer.dataset.mapStyle) {
                    opt.styles = JSON.parse(mapContainer.dataset.mapStyle)
                }

                let map = new google.maps.Map(document.getElementById('map'), opt)
                mapInit = true

                new google.maps.Marker({
                    position: position,
                    map,
                    title: mapContainer.dataset.title,
                })

            })
    }

}

function initSliderBien () {
    const swiper = new Swiper('.annonce_single .swiper-container', {
        speed: 600,
        navigation: {
            nextEl: '.button-next',
            prevEl: '.button-prev',
        },
        loop : true,
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
    });
    swiper.on('click', function() {
        swiper.slideNext();
    });
}

function initFilters () {
    if (!document.querySelector('.filters')) return

    //on séléctionne toutes les annonces
    const biensElement = document.querySelectorAll('.annonce')

    //on initialise les events listeners sur les filtres radios
    const radiosTypesDeBiens = document.querySelector('.type_de_bien .radios')
    radiosTypesDeBiens.addEventListener('change', set_radio_value)

    const radiosQuartier = document.querySelector('.quartier .radios')
    radiosQuartier.addEventListener('change', set_radio_value)

    const radiosEtat = document.querySelector('.etat .radios')
    if (radiosEtat) {
        radiosEtat.addEventListener('change', set_radio_value)
    }

/*

    // ancienne version de Tim

    //objet qui contiendra les filtres type classe (boutons radio)
    const classFilters = {}

    //transforme l'objet de classe en sélécteur
    function getClassFilters () {
        // //console.log('class filter', classFilters)
        return Object.values(classFilters).join('')
    }

    //gère les filtres type slider
    function filterData (element) {
        let result = true
        for (const attribute in dataFilters) {
            const elementValue = element.dataset[attribute]
            if (elementValue < dataFilters[attribute].min || elementValue > dataFilters[attribute].max) result = false
        }
        return result
    }

    //filtre les biens d'après les conditions fixées par les autres fonctions
    function filterBiens () {
        biensElement.forEach(bien => {
            const resultFilter = filterBien(bien)

            if (!resultFilter) {
                bien.classList.add('hide-from-search')
                return
            }

            bien.classList.remove('hide-from-search')
        })
    }

    //décide si un élément doit apparaitre ou non
    function filterBien (itemElement) {
        let selector = getClassFilters()
        let result
        //si il y a un filtre de classe on fait les deux
        if (selector) {
            result = itemElement.matches(selector) && filterData(itemElement)
        }
        //sinon
        else {
            result = filterData(itemElement)
        }
        return result

    }

    //objet contenant les filtres qui se rapportent aux attributs data
    const dataFilters = {}

    const divRanges = document.querySelectorAll('.range-fields')
    divRanges.forEach(container => {
        const inputs = document.querySelectorAll('input[type=range]')
        //gère l'input dans les sliders
        inputs.forEach((input) => {
            //on initialise son datafilter
            //on définit l'objet de filtre si il n'est encore pas définit
            if (typeof dataFilters[input.dataset.attribute] === 'undefined') {
                dataFilters[input.dataset.attribute] = {}
            }
            //en fonction du cas
            if (input.id.includes('lower')) {
                //on update les datafilters
                dataFilters[input.dataset.attribute].min = parseInt(input.value)
            } else {
                //on update les datafilters
                dataFilters[input.dataset.attribute].max = parseInt(input.value)
            }

            input.addEventListener('input', (e) => {
                const target = e.target
                // console.log("TARGET", target.dataset.attribute)
                //on update le prix correspondant dans l'interface
                const selector = '#' + target.dataset.valueId
                const valueInput = document.querySelector(selector)
                // console.log("VALUE INPUT", valueInput, "TARGER VALUE", target.value, "SELECTOR", selector);

                //on définit l'objet de filtre si il n'est encore pas définit
                if (typeof dataFilters[target.dataset.attribute] === 'undefined') {
                    dataFilters[target.dataset.attribute] = {}
                }

                const middleRange = document.querySelector(`#${target.dataset.range}`)

                //en fonction du cas
                if (target.id.includes('lower')) {
                    //on commence par vérifier qu'il est bien à sa place
                    const valueMax = document.querySelector('#' + target.id.replace('lower', 'upper')).value
                    target.value = Math.min(valueMax, target.value)

                    if (middleRange) {
                        //ensuite on update la modélisation de l'interval
                        middleRange.style.paddingLeft = `${(target.value / target.max) * target.clientWidth}px`
                        // console.log('PADDING LEFT', `${(target.value / target.max) * target.clientWidth}px`)
                    }

                    //on update les datafilters
                    dataFilters[target.dataset.attribute].min = parseInt(target.value)
                } else {
                    //on commence par vérifier qu'il est bien à sa place
                    const valueMin = document.querySelector('#' + target.id.replace('upper', 'lower')).value
                    target.value = Math.max(valueMin, target.value)

                    if (middleRange) {
                        //ensuite on update la modélisation de l'interval
                        middleRange.style.paddingRight = `${((target.max - target.value) / target.max) * target.clientWidth}px`
                    }
                    //on update les datafilters
                    dataFilters[target.dataset.attribute].max = parseInt(target.value)
                }

                if (valueInput) valueInput.value = target.value

                filterBiens()
            })
        })

        //gère l'input dans les input type number
        const inputsValue = document.querySelectorAll('input[type=number]')

        inputsValue.forEach((input) => {
            input.addEventListener('input', (e) => {
              //  console.log('e', e)
                const target = e.target
                // console.log('TARGET', target.value)
                //on update le prix correspondant dans l'interface
                const selector = '[data-value-id=' + target.id + ']'
                const valueInput = document.querySelector(selector)
                // console.log('VALUE INPUT', valueInput, 'TARGER VALUE', target.value, 'SELECTOR', selector)
                if (valueInput) valueInput.value = target.value

                //on définit l'objet de filtre si il n'est encore pas définit
                if (typeof dataFilters[valueInput.dataset.attribute] === 'undefined') {
                    dataFilters[valueInput.dataset.attribute] = {}
                }

                const middleRange = document.querySelector(`#${valueInput.dataset.range}`)

                //en fonction du cas
                if (valueInput.id.includes('lower')) {
                    //on commence par vérifier qu'il est bien à sa place
                    const valueMax = document.querySelector('#' + valueInput.id.replace('lower', 'upper')).value
                    valueInput.value = Math.min(valueMax, valueInput.value)

                    if (middleRange) {
                        //ensuite on update la modélisation de l'interval
                        middleRange.style.paddingLeft = `${(valueInput.value / valueInput.max) * valueInput.clientWidth}px`
                        // console.log('PADDING LEFT', `${(valueInput.value / valueInput.max) * valueInput.clientWidth}px`)
                    }

                    //on update les datafilters
                    dataFilters[valueInput.dataset.attribute].min = parseInt(valueInput.value)
                } else {
                    //on commence par vérifier qu'il est bien à sa place
                    const valueMin = document.querySelector('#' + valueInput.id.replace('upper', 'lower')).value
                    valueInput.value = Math.max(valueMin, valueInput.value)

                    if (middleRange) {
                        //ensuite on update la modélisation de l'interval
                        middleRange.style.paddingRight = `${((valueInput.max - target.value) / valueInput.max) * valueInput.clientWidth}px`
                    }
                    //on update les datafilters
                    dataFilters[valueInput.dataset.attribute].max = parseInt(valueInput.value)
                }

                target.value = valueInput.value

                filterBiens()
            })
        })
    })
*/
    //initialisation dispotisif d'ouverture
    const openButtons = document.querySelectorAll('.filter-container > button')
    const animationDuration = 400

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            toggleFiltre(button)
        })
    })

    let filterOpenButton

    //gère ouverture/fermeture des filtres
    function toggleFiltre (button, close = false) {
        const hiddenPart = button.parentElement.querySelector('[class*=hidden-part]')

        if (button.parentElement.classList.contains('open') || close) {
            jQuery(hiddenPart).slideUp(animationDuration)
            button.parentElement.style.removeProperty('left')
            //on attend que l'animation de fermeture soie finit pour enlever le placeholder
            setTimeout(() => {
                button.parentElement.classList.remove('open')
                button.parentElement.parentElement.style.setProperty('width', 'auto')
            }, animationDuration)

        } else {
            //on gère la taille du placeholder
            button.parentElement.parentElement.style.width = button.parentElement.getBoundingClientRect().width + 'px'

            //on ferme le filtre déjà ouvert => désactivé
            // if (filterOpenButton && filterOpenButton.classList && filterOpenButton !== button) {
            //     toggleFiltre(filterOpenButton, true)
            // }
            filterOpenButton = button
            // on toggle open sur le parent
            button.parentElement.classList.add('open')

            //on le décale si il sort de la fenêtre
            const boundingClient = button.parentElement.getBoundingClientRect()
            const offset = boundingClient.right - window.innerWidth + 50

            if (offset > 0 && window.innerWidth > 500) {
                button.parentElement.style.left = `-${offset}px`
            }
            jQuery(hiddenPart).slideDown()
        }

    }
    
}

function swat_load_range_filters() {
	
	var handle_min_price 	= jQuery( "#min-prix-value" );
	var handle_max_price 	= jQuery( "#max-prix-value" );
	
	var price_activated		= false;
	var surface_activated	= false;
	var nb_pieces_activated	= false;
	
	jQuery( ".slider_range_price" ).slider({
		range: true,
		create: function(event, ui) {
			jQuery(this).slider('option', 'min', jQuery(this).data('min'));
			jQuery(this).slider('option', 'max', jQuery(this).data('max'));
			jQuery(this).slider('option', 'values', [ jQuery(this).data('currentmin'), jQuery(this).data('currentmax') ]);
			handle_min_price.text( jQuery(this).data('currentmin') );
			handle_max_price.text( jQuery(this).data('currentmax') );
			jQuery(this).mouseup( function() {
				// lancement du filtrage
                filter_ads_list();
			});
		},
		slide: function( event, ui ) {
			price_activated = true;
			handle_min_price.text( ui.values[ 0 ] );
			handle_max_price.text( ui.values[ 1 ] );
			jQuery(this).parent().find(".slide_amount_min" ).val( ui.values[ 0 ] );
			jQuery(this).parent().find(".slide_amount_max" ).val( ui.values[ 1 ] );
		},
		stop: function( event, ui ) {
			handle_min_price.text( ui.values[ 0 ] );
			handle_max_price.text( ui.values[ 1 ] );
			jQuery(this).parent().find(".slide_amount_min" ).val( ui.values[ 0 ] );
			jQuery(this).parent().find(".slide_amount_max" ).val( ui.values[ 1 ] );
			// lancement du filtrage
            filter_ads_list();
		}
	});
	
	var handle_min_surface	= jQuery( "#min-surface-value" );
	var handle_max_surface 	= jQuery( "#max-surface-value" );
	
	jQuery( ".slider_range_surface" ).slider({
		range: true,
		create: function(event, ui) {
			jQuery(this).slider('option', 'min', jQuery(this).data('min'));
			jQuery(this).slider('option', 'max', jQuery(this).data('max'));
			jQuery(this).slider('option', 'values', [ jQuery(this).data('currentmin'), jQuery(this).data('currentmax') ]);
			handle_min_surface.text( jQuery(this).data('currentmin') );
			handle_max_surface.text( jQuery(this).data('currentmax') );
			jQuery(this).mouseup( function() {
				// lancement du filtrage
                filter_ads_list();
			});
		},
		slide: function( event, ui ) {
			surface_activated = true;
			handle_min_surface.text( ui.values[ 0 ] );
			handle_max_surface.text( ui.values[ 1 ] );
			jQuery(this).parent().find(".slide_amount_min" ).val( ui.values[ 0 ] );
			jQuery(this).parent().find(".slide_amount_max" ).val( ui.values[ 1 ] );
		},
		stop: function( event, ui ) {
			handle_min_surface.text( ui.values[ 0 ] );
			handle_max_surface.text( ui.values[ 1 ] );
			jQuery(this).parent().find(".slide_amount_min" ).val( ui.values[ 0 ] );
			jQuery(this).parent().find(".slide_amount_max" ).val( ui.values[ 1 ] );
			// lancement du filtrage
            filter_ads_list();
		}
	});
	
	var handle_min_nb_pieces	= jQuery( "#min-nb-piece-value" );
	var handle_max_nb_pieces 	= jQuery( "#max-nb-piece-value" );
	
	jQuery( ".slider_range_nb_pieces" ).slider({
		range: true,
		step: 0.5,
		create: function(event, ui) {
			jQuery(this).slider('option', 'min', jQuery(this).data('min'));
			jQuery(this).slider('option', 'max', jQuery(this).data('max'));
			jQuery(this).slider('option', 'values', [ jQuery(this).data('currentmin'), jQuery(this).data('currentmax') ]);
			handle_min_nb_pieces.text( jQuery(this).data('currentmin') );
			handle_max_nb_pieces.text( jQuery(this).data('currentmax') == 5.5 ? '5+' : jQuery(this).data('currentmax') );
			jQuery(this).mouseup( function() {
				// lancement du filtrage
                filter_ads_list();
			});
		},
		slide: function( event, ui ) {
			nb_pieces_activated = true;
			handle_min_nb_pieces.text( ui.values[ 0 ] );
			handle_max_nb_pieces.text( ui.values[ 1 ] );
			jQuery(this).parent().find(".slide_amount_min" ).val( ui.values[ 0 ] );
			jQuery(this).parent().find(".slide_amount_max" ).val( ui.values[ 1 ] );
		},
		stop: function( event, ui ) {
			handle_min_nb_pieces.text( ui.values[ 0 ] );
			handle_max_nb_pieces.text( ui.values[ 1 ] );
			jQuery(this).parent().find(".slide_amount_min" ).val( ui.values[ 0 ] );
			jQuery(this).parent().find(".slide_amount_max" ).val( ui.values[ 1 ] );
			// lancement du filtrage
            filter_ads_list();
		}
	});
	
	jQuery('.ui-slider-handle').mouseenter( function() {
		jQuery(this).find('span').fadeIn(100);
	});
	
	jQuery('.ui-slider-handle').mouseout( function() {
		jQuery(this).find('span').fadeOut(100);
	});
	
}

function set_radio_value( e ) {

    if( e.target.name == 'type_bien_radio' ) {
        document.querySelectorAll('#filter_type')[0].setAttribute('value', e.target.value);
    }
    if( e.target.name == 'quartier_radio' ) {
        document.querySelectorAll('#filter_quartier')[0].setAttribute('value', e.target.value);
    }
    if( e.target.name == 'etat_radio' ) {
        document.querySelectorAll('#filter_etat')[0].setAttribute('value', e.target.value);
    }

    filter_ads_list();
}

function filter_ads_list() {

    // on récupère la liste des biens
    const biensElement = document.querySelectorAll('.annonce');

    // ensuite, on récupère les filtres en cours

    const filter_type =  document.querySelectorAll('#filter_type')[0].getAttribute("value");
    const filter_quartier =  document.querySelectorAll('#filter_quartier')[0].getAttribute("value");
    const filter_etat_element = document.querySelector('#filter_etat');
    const filter_etat = filter_etat_element ? filter_etat_element.getAttribute("value") : '';
    const filter_prix_min =  parseInt(document.querySelectorAll('#filter_prix_min')[0].getAttribute("value"));
    const filter_prix_max =  parseInt(document.querySelectorAll('#filter_prix_max')[0].getAttribute("value"));
    const filter_surface_min =  parseInt(document.querySelectorAll('#filter_surface_min')[0].getAttribute("value"));
    const filter_surface_max =  parseInt(document.querySelectorAll('#filter_surface_max')[0].getAttribute("value"));
    const filter_nb_pieces_min =  parseInt(document.querySelectorAll('#filter_nb_pieces_min')[0].getAttribute("value"));
    const filter_nb_pieces_max =  parseInt(document.querySelectorAll('#filter_nb_pieces_max')[0].getAttribute("value"));

    biensElement.forEach(bien => {

        // on les masque tous
        bien.classList.add('hide-from-search');
        
        // puis on affiche ceux qui respectent les filtres
        if( ( ( bien.getAttribute('data-type') == filter_type && filter_type != '') || filter_type == '' ) &&
            ( ( bien.getAttribute('data-quartier') == filter_quartier && filter_quartier != '') || filter_quartier == '' ) &&
            ( ( bien.getAttribute('data-etat') == filter_etat && filter_etat != '') || filter_etat == '' ) &&
            bien.getAttribute('data-prix') >= filter_prix_min && 
            bien.getAttribute('data-prix') <= filter_prix_max && 
            bien.getAttribute('data-surface') >= filter_surface_min && 
            bien.getAttribute('data-surface') <= filter_surface_max && 
            bien.getAttribute('data-nb-piece') >= filter_nb_pieces_min && 
            bien.getAttribute('data-nb-piece') <= filter_nb_pieces_max ) 
            {
                bien.classList.remove('hide-from-search')
        }
        
    })
}

function initProgressionBar () {
    if (!document.querySelector('.progression-indicator')) return

    //on récupère les indicateurs de prog
    const sectionsProgress = {}
    document.querySelectorAll('.progression-indicator > div.element-progression').forEach((section) => {
        //on les associe dans l'objet grace à leur ancre pour les retrouver facilement
        sectionsProgress[section.dataset.ancre] = section
    })

    //on récuère toute les sections de la page
    const sections = document.querySelectorAll('.section-progression')

    window.addEventListener('scroll', (e) => {
        if(window.innerWidth < 1280) return;

        //si on est à la fin de la page on les mets toutes au max (rend la chose plus intuitive)
        if (window.scrollY + window.innerHeight >= document.body.clientHeight - 10) {
            sections.forEach((element) => {
                //on met tout les rations à 100
                sectionsProgress[element.id].style.setProperty('--progression-value', '100%')
                sectionsProgress[element.id].classList.add('active')
            })
            return
        }

        sections.forEach((element) => {
            //on calcule le ratio de lecture de chaque section
            let readRatio = Math.max(0, Math.min(1, (window.scrollY - element.offsetTop + 170) / element.clientHeight))

            //on l'attribue a la section correspondante dans l'indicateur
            sectionsProgress[element.id].style.setProperty('--progression-value', readRatio * 100 + '%')
            if (readRatio > 0) sectionsProgress[element.id].classList.add('active')
            else sectionsProgress[element.id].classList.remove('active')

        })

    })

}

function initFaqServices () {
    const blocs = document.querySelectorAll('.bloc-faq-services')

    const params = {
        openClassName: 'element-open',
        animationDuration: 600
    }

    blocs.forEach((bloc) => {
        const elements = bloc.querySelectorAll('.element')

        elements.forEach((element) => {
            const visiblePart = element.querySelector('.visible-part')
            const hiddenPart = element.querySelector('.hidden-part')

            visiblePart.addEventListener('click', (e) => {
                $(hiddenPart).slideToggle(params.animationDuration)
                element.classList.toggle(params.openClassName)
            })

        })
    })
}

function initNosEquipes () {
    const blocs = document.querySelectorAll('.bloc-nos-equipes.mode-ca')

    const params = {
        openClassName: 'open',
        animationDuration: 600
    }

    blocs.forEach((bloc) => {
        const elements = bloc.querySelectorAll('.member')

        elements.forEach((element) => {
            const hiddenPart = element.querySelector('.hidden-part')

            element.addEventListener('click', toggleVisibility)

            function toggleVisibility () {
                $(hiddenPart).slideToggle(params.animationDuration)
                element.classList.toggle(params.openClassName)
            }

        })
    })
}

function initBrochures () {
    const brochuresContainer = document.querySelectorAll('.brochures-container')
    gsap.registerPlugin(ScrollTrigger)

    brochuresContainer.forEach((container) => {
        //on regarde si il y a bien des brochures
        if (!container.childNodes || container.childNodes.length === 0) return

        //on récupère toutes les brochures du container
        let brochures = [...container.childNodes].filter(child => {
            if (!child.className) return false
            return child.classList.contains('brochure')
        })

        //on initialise la timeline d'animation
        let timeline = gsap
            .timeline({
                scrollTrigger: {
                    trigger: container,
                    start: 'center center',
                    end: 'bottom top',
                    pin: true,
                    scrub: 2,
                },
            })

        const offsetRotation = 5

        //pour chaque brochure on initialise son animation
        brochures.forEach((brochure, index) => {
            //animation de sortie
            timeline.to(brochure, {
                translateY: '-200px',
                opacity: 0,
                ease: 'power2.out',
                duration: 3
            })

            if (brochures[index + 1]) {
                //si ce n'est pas la dernière brochure on ajoute l'animation faisant disparaitre le cache de la suivante
                timeline.to(brochures[index + 1], {
                    backgroundColor: 'white',
                    color: 'black',
                    duration: 1
                })

                //et pareil pour le bouton
                timeline.to(brochures[index + 1].getElementsByTagName('a'), {
                    borderColor: 'black',
                    duration: 0.1,
                    delay: 5
                })
            }

            //on donne l'orientation : le premier est droit
            if (index === 0) return
            //on prend un chiffre aléatoire pour appliquer un décalage plus "réaliste"
            const intensityOffset = Math.floor(Math.random() * 5)

            gsap.to(brochure, {
                rotate: `${intensityOffset * offsetRotation}deg`
            })
        })
        //tentative de lien pour la navigation
        gsap.utils.toArray('.brochures-naviguation li a').forEach(function (a) {
            a.addEventListener('click', function (e) {
                gsap.to(`${e.target.getAttribute('href')}`,
                    {
                        backgroundColor: 'white',
                        color: 'black',
                    })
            })
        })
    })

}

function initMenu () {
    let button = document.querySelector('.menu-burger')

    //pour éviter que le fond de couleur s'enlève au hover du lien correspondant on surcharge le comportement
    button.addEventListener('mouseenter', forceHover(true))
    button.addEventListener('mouseleave', forceHover(false))

    function forceHover(sens) {
        const leftPart =  document.querySelector('body.home #left-part');
        if(!leftPart || !leftPart.classList) return;

        if(sens) {
            leftPart.classList.add('force-hover')
            return;
        }

        leftPart.classList.remove('force-hover')
    }

    //ou

    const linksHeader = document.querySelectorAll('header nav a, .footer-fixed a, .hdr-logo-link')
    linksHeader.forEach(linkHeader => {
        linkHeader.addEventListener('click', () => {
            if (document.body.classList.contains('menu-open')) {
                toggleMenu()
            }
        })
    })

    //pour que au click sur les liens le menu se ferme
    let linksMenu = document.querySelectorAll('.menu_container .menu-item a')

    linksMenu.forEach(link => {
        link.addEventListener('click', closeMenuLink)
    })

    function closeMenuLink (e) {
        if (window.innerWidth > 1280) {
            toggleMenu()
            return
        }

        const header = document.querySelector('header')
        const parentElement = e.target.offsetParent
        if (parentElement.className.includes('menu-item') && !parentElement.className.includes('open-mobile')) {
            e.preventDefault()
            parentElement.classList.add('open-mobile')
            toggleBackArrow()
            return
        }

        toggleMenu()
        parentElement.classList.remove('open-mobile')
    }

    const menu = document.querySelector('.menu_container')
    //ajout des réseaux sociaux
    const reseauxSociaux = JSON.parse(menu.dataset.reseauxSociaux)
    const rscScxEl = menu.querySelector('a[href=\'#reseaux-sociaux\']')

    if (rscScxEl) {
        let container = document.createElement('div')
        container.classList.add('container-rsx-scx')
        reseauxSociaux.forEach(rsx => {
            let a = document.createElement('a')
            a.href = rsx.url.url
            a.target = rsx.url.target
            container.appendChild(a)

            let img = document.createElement('img')
            img.src = rsx.icone.url
            a.appendChild(img)
        })

        rscScxEl.parentElement.appendChild(container)
    }

    //gestion ouverture du menu
    button.addEventListener('click', toggleMenu)

    function toggleMenu () {

        const textDaudin = document.querySelector('.mouse-follow-effect')


        if (menu.className.includes('tidied')) {
            //si il est rangé
            menu.classList.remove('tidied')
            menu.classList.remove('closed')
            button.classList.add('open')
            document.body.classList.add('menu-open')

            //change acitvation lien
            initActiveLink()
            //bloque le menu
            activateDaudinEffect()
            //add event listener pour fermer le menu sur daudin

            //met le titre au bon endroit
            gsap.to(textDaudin, {
                '--position-title': calculateMax() / 2 + "px",
                duration: 0.3
            })

            return
        }

        //gère la fermeture

        //enlève la flèche de retour si elle est la
        const svgElement = { target: document.querySelector('.icon-arrow-back') }
        if (svgElement.target) removeSvg(svgElement)

        //ferme les sous menus
        const menuItem = document.querySelectorAll('.menu-item')
        menuItem.forEach((item) => {
            item.classList.remove('open-mobile')
        })

        //referme tout
        menu.classList.add('closed')
        button.classList.remove('open')
        document.body.classList.remove('menu-open')
        setTimeout(() => {menu.classList.add('tidied')}, 700)

        activateDaudinEffect()

        placeTitle()
    }

    const menuContainer = document.querySelector('.menu_container')

    //flèche retour sous menus
    function toggleBackArrow () {
        const svg = menuContainer.dataset.arrowBack

        //créer un élément svg, ajoute le svg du backend et l'ajoute au doc
        let svgElement = document.createElement('svg')
        menuContainer.appendChild(svgElement)
        svgElement.outerHTML = svg
        svgElement = menuContainer.querySelector('svg')
        svgElement.classList.add('icon-arrow-back')
        gsap.from(svgElement, {
            translateX: -300
        })

        svgElement.addEventListener('click', removeSvg)

    }

    //enlève le svg à la fermeture du sous menus
    function removeSvg (e) {
        document.querySelector('.open-mobile').classList.remove('open-mobile')
        gsap.timeline()
            .to(e.target, {
                translateX: -300
            })
            .call(function () {
                menuContainer.removeChild(e.target)
            })

    }
}

//bouge le titre de place et retourne le fait que ce soit la home pag ou non
function placeTitle () {
    const textDaudin = document.querySelector('.mouse-follow-effect')
    let daudinPos = document.querySelector('.main_content').dataset.positionDaudin

    // console.log('tsteda', textDaudin)
    //si mobile il est au milieu
    if (window.innerWidth < 1280) {
        // console.log('mobile')
        centerDaudin()
        return
    }

    if (!document.body.className.includes('menu-open')) {
        if (document.body.className.includes('single-rdr_annnonce')) {
            daudinPos = 'gauche'
        }
        // console.log('coucou')

        //en fonction du parametre backend
        switch (daudinPos) {
            case 'gauche' :
                // console.log('gauche')
                gsap.to(textDaudin, {
                    '--position-title': 0,
                    duration: 0.3
                })
                break
            case 'droite':
                gsap.to(textDaudin, {
                    '--position-title': calculateMax() + "px",
                    duration: 0.3
                })
                break
            case 'centre':
            default: {
                centerDaudin()
            }
        }

        return
    }

    function centerDaudin () {
        // console.log('center')
        gsap.to(textDaudin, {
            '--position-title': calculateMax() / 2 + "px",
            duration: 0.3
        })
    }

    return true
}

window.addEventListener('resize', placeTitle)

//gère les adaptation d'elements au scroll (daudin, footer)
function ajustElementOnScroll () {
    const daudin = document.querySelector('.hdr-logo')
    let daudinOutOfScreen = false

    const footer = document.querySelector('footer > .footer-fixed')
    let previousPos = window.scrollY
    let footerOutOfScreen = false

    window.addEventListener('scroll', () => {
        //si on est sur mobile ou sur la page d'accueil ou qu'il n'est pas centré on ne fait rien
        if (document.body.classList.contains('home')) return

        //on regarde si le titre est centré
        const titleCenter = document.querySelector('.main_content').dataset.positionDaudin === 'centre'

        //si on est au dessus de l'écran
        if (window.scrollY <= 0) {
            // si on est en haut on affiche le footer
            gsap.to(footer, {
                translateY: '0'
            })

            //si le titre est bien hors de l'écran on l'affiche
            if (!daudinOutOfScreen || !titleCenter || window.innerWidth < 1280) return
            gsap.to(daudin, {
                translateY: '0',
                onComplete: () => {
                    daudin.style.display = 'inline'
                }
            })
            daudinOutOfScreen = false
            return
        }

        //si on est scrollé on enlève le titre de l'écran
        //on vérifie qu'il n'est pas déjà sorti dans l'écran et qu'on est bien sur une page ou le titr est centré
        if (!daudinOutOfScreen && titleCenter && window.innerWidth > 1280) {
            gsap.to(daudin, {
                translateY: '-100vh',
                display: 'block'
            })
            daudinOutOfScreen = true
        }

        //on regarde le sens de scroll et on ajuste la position du footer
        //si on scroll vers le bas
        if (previousPos < window.scrollY) {
            gsap.to(footer, {
                translateY: '100vh',
                ease: 'sine.in'
            })
        }
        //sinon on l'affiche
        else {
            gsap.to(footer, {
                translateY: '0'
            })
        }

        previousPos = window.scrollY;
    })
}

function initPageTransition () {
    
    //résolution bug hover liens header
    function linkHoverLinktoHomePage () {
        let links = document.querySelectorAll('header nav a')

        links.forEach(function (link) {

            link.addEventListener('mouseenter', (e) => {

                const link = e.target
                const homePart = document.querySelector('body.home #' + link.dataset.partieLie)
                if (homePart) {
                    homePart.classList.add('force-hover')
                }
                // const homePartP = document.querySelector('body.home #' + link.dataset.partieLie)
                // if (homePartP) {
                //     homePartP.classList.add('force-hover-p')
                // }

                const bg_transition = document.querySelector('.bg_transition')

                if (!bg_transition) return

                //console.log('CLASS LINK', link.className)
                //console.log('alors', link.className.includes('inactive'), gsap.isTweening(bg_transition), document.querySelector('body.home'))
                if (!gsap.isTweening(bg_transition) && !document.querySelector('body.home')) {
                    //console.log('coucou')
                    const mainContent = document.querySelector('.main_content')
                    if (mainContent) {
                        mainContent.classList.add(link.dataset.pageClass)
                    }
                    // //console.log("LEFT", link.dataset.pageClass)
                    if (link.dataset.pageClass === 'left-page') {
                        bg_transition.style.left = 0
                        bg_transition.style.removeProperty('right')
                    } else {
                        //console.log('RIGHT')
                        bg_transition.style.removeProperty('left')
                        bg_transition.style.right = 0
                    }

                    bg_transition.style.backgroundColor = link.dataset.colorPartieLie
                    gsap.timeline().to(bg_transition, {
                        width: '4rem',
                        duration: 0.2
                    })
                }

            })

            link.addEventListener('mouseleave', () => {
                const homePart = document.querySelector('body.home #' + link.dataset.partieLie)
                if (homePart) {
                    homePart.classList.remove('force-hover')
                }
                // const homePartP = document.querySelector('body.home #' + link.dataset.partieLie)
                // if (homePartP) {
                //     homePartP.classList.remove('force-hover-p')
                // }

                const bg_transition = document.querySelector('.bg_transition')

                if (!bg_transition) return

                if (gsap.isTweening(bg_transition)) {return}
                gsap.timeline().to(bg_transition, {
                    width: '0',
                    duration: 0.2
                })

            })

        })
    }

    linkHoverLinktoHomePage()

    function checkActivationLink () {
        const liensHeader = document.querySelectorAll('header nav > a')
        liensHeader.forEach((link) => {
            if (window.location.href.startsWith(link.href)) {
                link.classList.add('active')
                link.classList.remove('inactive')
            } else {
                link.classList.remove('active')
                link.classList.add('inactive')
            }
        })
    }

    checkActivationLink()

    //hooks barba
    function afterLeave (data) {
        //pour mettre les bonnes classes au body
        let nextHtml = data.next.html
        let response = nextHtml.replace(/(<\/?)body( .+?)?>/gi, '$1notbody$2>', nextHtml)
        let bodyClasses = $(response).filter('notbody').attr('class')
        $('body').attr('class', bodyClasses)

        //maj lien inactif (page actuelle) header
        checkActivationLink()
        initPos()
    }

    function after () {
        try {
            placeTitle()//pour blocque
            initAll()
        } catch (e) {
            console.error('ERROR INITIALISATION', e)
        }

    }

    function before () {
        //console.log('OTHER ANIMATION KILLED')
        gsap.killTweensOf('.bg_transition')
    }

    function beforeEnter (data) {
        //console.log('COUCOU')
        data.current.container.remove()
    }

    //initialisation barba
    barba.init({
        timeout: 5000,
        transitions: [
            {
                name: 'home-to-lieu-vie',
                from: {
                    namespace: [
                        'home',
                    ]
                },
                to: {
                    namespace: [
                        'lieu-vie',
                    ]
                },
                leave (data) {
                    return gsap.timeline({
                        // totalDuration: 1.24
                    })
                        .to(data.current.container.querySelector('#left-part p'), {
                            clipPath: 'inset(0 0 100% 0)',
                            duration: 0.1
                        })
                        .to(data.current.container.querySelector('#link-left-part'), {
                            width: '100%',
                            zIndex: 1,
                            duration: 0.21
                        })
                        .to(data.current.container.querySelector('#left-part p'), {
                            clipPath: 'inset(0 0 100% 0)',
                            duration: 1.07
                        })
                },
                enter (data) {
                    return gsap.from(data.next.container.querySelector('.page_content'), {
                        '--clip-path-value': '100%',
                        duration: 1,
                        ease: 'power4.in'
                    })
                },
                after: after,
                afterLeave: afterLeave,
                beforeEnter: beforeEnter,
                before: before
            },
            {
                name: 'votre-patrimoine-to-votre-lieu-de-vie',
                to: {
                    namespace: [
                        'lieu-vie',
                        'pat-immo',
                    ]
                },
                leave (data) {
                    return gsap.timeline().call(() => {
                        // //console.log(data)
                        document.querySelector('header .hdr-logo-link').classList.add('bg-transparent')
                        // document.querySelector(".main_content").classList.add(data.next.namespace)
                        const bg_transition = document.querySelector('.bg_transition')
                        // bg_transition.style.backgroundColor = link.dataset.colorPartieLie;
                        if (data.next.namespace.includes('vie')) {
                            bg_transition.style.left = 0
                        } else {
                            bg_transition.style.right = 0
                        }
                    }).to(data.current.container.querySelector('.bg_transition'), {
                        width: '100%'
                    }, 1)
                },
                enter (data) {
                    return gsap.timeline().call(() => {
                        document.querySelector('header .hdr-logo-link').classList.remove('bg-transparent')
                        // document.querySelector(".main_content").classList.remove(data.next.namespace)
                    }).from(data.next.container.querySelector('.page_content'), {
                        '--clip-path-value': '100%',
                        duration: 1,
                        ease: 'power4.in'
                    })
                },
                after: after,
                afterLeave: afterLeave,
                beforeEnter: beforeEnter,
                before: before
            },
            {
                name: 'votre-patrimoine-to-votre-lieu-de-vie',
                from: {
                    namespace: [
                        'lieu-vie',
                        'pat-immo',
                    ]
                },
                to: {
                    namespace: [
                        'home'
                    ]
                },
                leave (data) {
                    return gsap.to(data.current.container, {
                        opacity: 0
                    })
                },
                enter (data) {
                    return gsap.from(data.next.container, {
                        opacity: 0
                    })
                },
                after: after,
                afterLeave:afterLeave ,
                beforeEnter: (data) => {
                    try {
                        placeTitle()
                        beforeEnter(data)
                    } catch(e) {
                        console.error(e)
                    }
                },
                before: before
            },
            {
                name: 'home-to-votre-patri',
                from: {
                    namespace: [
                        'home',
                    ]
                },
                to: {
                    namespace: [
                        'pat-immo',
                    ]
                },
                leave (data) {
                    return gsap.timeline().to(data.current.container.querySelector('#right-part p'), {
                        clipPath: 'inset(0 0 100% 0)',
                    }).to(data.current.container.querySelector('#link-right-part'), {
                        width: '100%',
                        zIndex: 1
                    }).to(data.current.container.querySelector('#right-part p'), {
                        clipPath: 'inset(0 0 100% 0)',
                    })
                },
                enter (data) {
                    return gsap.from(data.next.container.querySelector('.page_content'), {
                        '--clip-path-value': '100%',
                        duration: 1,
                        ease: 'power4.in'
                    })
                },
                after: after,
                afterLeave: afterLeave,
                beforeEnter: beforeEnter,
                before: before
            },
            {
                name: 'default',
                leave (data) {
                    return gsap.to(data.current.container, {
                        opacity: 0
                    })
                },
                enter (data) {
                    return gsap.from(data.next.container, {
                        opacity: 0
                    })
                },
                after: after,
                afterLeave: afterLeave,
                beforeEnter: beforeEnter,
                before: before
            },

        ],
    })

}




function reload_page_scripts() {

    // let's make call to the global gwrf variable visible after enabling Gravity Forms

    (function ($) {

        gwrf = function (args) {
            // prototype arguments, created when we instantiate it
            this.formId = args.formId;
            this.spinnerUrl = args.spinnerUrl;
            this.refreshTime = args.refreshTime;
            this.refreshTimeout = null;
            
            // form wrapper class followed by the form id
            this.formWrapper = $('#gform_wrapper_' + this.formId);
            this.staticElem = this.formWrapper.parent();
            
            // we want to make sure that we'll have the cloned form html
            this.formHtml = $('<div />').append(this.formWrapper.clone()).html();

            this.spinnerInitialized = false;

            this.init = function () {

                var gwrf = this;
                
                // this is Gravity Forms .js hook which we bind a function call to
                // once the AJAX confirmation was loaded we'll trigger the function
                $(document).bind('gform_confirmation_loaded', function (event, formId) {
                    
                    // let's make sure we'll reload the right form
                    if (formId != gwrf.formId || gwrf.refreshTime <= 0)
                        return;
                    
                    // let's reload the form after some time
                    gwrf.refreshTimeout = setTimeout(function () {
                        gwrf.reloadForm();
                    }, gwrf.refreshTime * 1000);

                });
                
                // let's make sure that the form will be closed only after the .closed-lightbox element was
                // clicked on. This might be a button in the top right corner of our popup
                $('.closed-lightbox').on('click', function (event) {
                    event.preventDefault();
                    gwrf.reloadForm();
                });

            };

            // heart of our functionality
            this.reloadForm = function () {
                
                // let's check if the confirmation message has already been created
                if (this.staticElem.find('.gform_confirmation_message_' + this.formId).length) {
                    if (this.refreshTimeout)
                        clearTimeout(this.refreshTimeout);
                    
                    // let's look for the confirmation element and get the HTML of the parent form
                    this.staticElem.find('.gform_confirmation_message_' + this.formId)
                            .wrap('<div />').parent().html(this.formHtml);
                    // let's get rid of the HTML of the FORM
                    this.staticElem.find('#gform_wrapper_' + this.formId).unwrap();
                    
                    // let's rerender the form
                    $(document).trigger('gform_post_render', [this.formId, 0]);
                    // if we had datepicker let's reinstantiate it
                    if (window['gformInitDatepicker'])
                        gformInitDatepicker();
                }
            };

            // utility used to display the "spinner" loading image
            this.initSpinner = function () {

                var gwrf = this;

                this.staticElem.on('submit', '#gform_' + this.formId, function () {
                    $('#gform_submit_button_' + gwrf.formId).attr('disabled', true).after('<' + 'img id=\"gform_ajax_spinner_' + gwrf.formId + '\"  class=\"gform_ajax_spinner\" src=\"' + gwrf.spinnerUrl + '\" />');
                    gwrf.formWrapper.find('.gform_previous_button').attr('disabled', true);
                    gwrf.formWrapper.find('.gform_next_button').attr('disabled', true).after('<' + 'img id=\"gform_ajax_spinner_' + gwrf.formId + '\"  class=\"gform_ajax_spinner\" src=\"' + gwrf.spinnerUrl + '\" alt=\"\" />');
                });

            };

            this.init();

        };
        
        // if our document is ready we'll create a new form manually
        jQuery(document).ready(function () {
            var forms = document.querySelectorAll('form[id^="gform"]');
            if (!forms.length) {
                return;
            }
            // Loop through all forms in the Popup and reload them.
            forms.forEach(function() {
                var form = jQuery(this);
                new gwrf({"formId": form.attr('id'), "spinnerUrl": "{spinner_url}", "refreshTime": 0});
            });
        });

    })(jQuery);

}

//effet titre qui suit souris
function initDaudinEffect () {
    const textDaudin = document.querySelector('.mouse-follow-effect')
    const textDaudinSpan = document.querySelector('.mouse-follow-effect svg')

    const boundingRect = textDaudinSpan.getBoundingClientRect()
    const header = document.querySelector('header')
    let paddingHeader, paddingHeaderValue;

    const moveTitle = debounce(function () {
        //vérifie les conditions
        if(!isDaudinEffect) return;
        const offset = Math.max(0, Math.min(clientX - (boundingRect.width / 2) - paddingHeaderValue, maxValue))
        gsap.to(textDaudin, {
            '--position-title': offset + "px",
            duration: 0.3
        })
    }, 5)

    function calculatePadding() {
        paddingHeader = window.getComputedStyle(header).paddingLeft
        paddingHeaderValue = parseInt(paddingHeader.slice(0, paddingHeader.length - 2))
    }
    calculatePadding()

    window.addEventListener('mousemove', moveTitle )

    window.addEventListener('resize', () => {
        setMaxValueDaudinEffect()
        calculatePadding()
    })

}

let isDaudinEffect = false;
function activateDaudinEffect() {
    isDaudinEffect =  document.body.className.includes('home') && !document.body.className.includes('menu-open') && window.innerWidth > 1280;
   // console.log("isDaudin", isDaudinEffect)
}
let maxValue;
function setMaxValueDaudinEffect(){
    maxValue = calculateMax();
}
function calculateMax () {
    const textDaudin = document.querySelector('.mouse-follow-effect')
    const textDaudinSpan = document.querySelector('.mouse-follow-effect svg')
    return textDaudin.getBoundingClientRect().width - (daudinCenterLogoWidth)
}

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// function daudin_load_ajax_functions () {
//
//   jQuery('#example').click(function () {
//
//     var action = 'ajax_example_action'
//     var parameter1 = $('#parameter1').attr('data-parameter1')	// or $('#parameter1').val();
//     var parameter1 = $('#parameter2').attr('data-parameter2')	// or $('#parameter2').val();
//     var parameter1 = $('#parameter3').attr('data-parameter3')	// or $('#parameter3').val();
//
//     jQuery('.ajax-waiting-loader').removeClass('hide')
//
//     jQuery.ajax({
//       url: ajaxurl,
//       type: 'POST',
//       data: {
//         'action': action,
//         'parameter1': parameter1,
//         'parameter2': parameter2,
//         'parameter3': parameter3,
//       }
//     }).done(function (response) {
//       jQuery('.ajax-waiting-loader').addClass('hide')
//       jQuery('.results_div').html(response)
//     })
//   })
//
// }





