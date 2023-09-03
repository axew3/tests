<!-- IF not W3VV_VIEWTOPIC_MODE  -->
<script>
    // !! NEED to MATCH on DOM (pagination on prosilver theme)
  if ( document.querySelector(".bar-top > .pagination > ul > li > span") != null )
  {

    // !! NEED to MATCH on DOM
    // actual page we land on, in the pagination buttons
     var w3vv_actP = document.querySelector(".bar-top > .pagination > ul > li > span");
    // !! NEED to MATCH on DOM *
     var w3vv_startPage = w3vv_actP.innerHTML; // extracted text page num, where we are landing on
    // all pages, on the 'pagination' buttons
    // !! NEED to MATCH on DOM
     var w3vv_allPages = document.querySelectorAll(".bar-top > .pagination > ul > li");
     var w3vv_PagesCount = 0;

  // When the pagination menu change due to many pages, the first span contain ... (NaN)
  // Get the second then, in the hope that this is the good one
  if(isNaN(w3vv_startPage)){
   // !! NEED to MATCH on DOM
   let w3vv_actPP = document.querySelectorAll(".bar-top > .pagination > ul > li > span");
    w3vv_startPage = w3vv_actPP[1].innerHTML;
    //alert('w3vv_actPP '+w3vv_actPP[1].innerHTML);
  }

 /*  if ( document.querySelector(".bar-top > .pagination > ul > li > span:nth-of-type(1)") != null )
  {
   alert('span:nth-of-type(1)');
   let t = document.querySelector(".bar-top > .pagination > ul > li > span:nth-of-type(1)");
   //.previousElementSibling
    alert('t.nextElementSibling'+t.nextElementSibling);

    var q = $( ".bar-top > .pagination > ul > li > span:nth-of-type(1)" ).prev();

    console.log(q);
    //alert(t.previousElementSibling);

  }*/

    const W3VVFIRST_ONTOPIC = "{W3VVFIRST_ONTOPIC}";
    const W3VVLAST_ONTOPIC = "{W3VVLAST_ONTOPIC}";
    const W3VV_DIRTOPIC_SORT = "{W3VV_DIRTOPIC_SORT}";
    const W3VV_DIRPOST_SORT = "{W3VV_DIRPOST_SORT}";
    const W3VVPP_VT_POSTS_X_PAGE = "{W3VVPP_VT_POSTS_X_PAGE}";
    //var W3VV_ADM_TOTPAGES_NUM = "{W3VV_ADM_TOTPAGES_NUM}";
    //var W3VV_USR_TOTPAGES_NUM = "{W3VV_USR_TOTPAGES_NUM}";
    var W3VVPP_VT_PLIST_PID_ARYFIRST = "{W3VVPP_VT_PLIST_PID_ARYFIRST}";
    var W3VVPP_VT_PLIST_PID_ARYLAST = "{W3VVPP_VT_PLIST_PID_ARYLAST}";

    var W3VVPP_VT_REV_PLIST_PID_ARYFIRST = "{W3VVPP_VT_REV_PLIST_PID_ARYFIRST}";
    var W3VVPP_VT_REV_PLIST_PID_ARYLAST = "{W3VVPP_VT_REV_PLIST_PID_ARYLAST}";
    let w3vv_ticking = false;

  // To record the list of retrieved pages: will be intialized with the (class active) first page, where we land on
    var w3vv_loadedPages_Ary = {};
    var w3vv_loadedPages_Aryxfl = {}; // used to fill lacking array keys when there are two ellipsis

  //console.log('w3vv_loadedPages_Ary -> ' + w3vv_loadedPages_Ary['startPage']);

  // Build an array of pages from the pagination buttons:
  // Exclude dropdown-container (jump to page) button, previous and next arrows/buttons
  // key = page // value = url
  // add the actual landed page into the w3vv_loadedPages_Ary
    var w3vv_pagesUrlAry = [];

    // !! Initialize the first array element with a value on 0 or loose pages index order
    //  * so substract 1 just below for w3vv_PagesCount value
    w3vv_pagesUrlAry[0] = 'w3';
    w3vv_loadedPages_Ary[0] = 'w3';
    w3vv_loadedPages_Aryxfl[0] = 'w3';

    /*for (el of w3vv_allPages) {
     if( el.className != 'dropdown-container' && el.className != 'arrow previous' && el.className != 'arrow next' ){
        if(el.className == 'active'){
         w3vv_pagesUrlAry[el.innerText] = el.baseURI;
         w3vv_loadedPages_Ary[el.innerText] = el.innerText;
        } else {  w3vv_pagesUrlAry[el.innerText] = el.firstChild.href; }
     }
    }*/


    var isPrevEllips = 0;
    var isPrevEllipses0 = 0;
    var isNextEllipses0 = 0;
    var isPrevEllipses = 0;
    var isNextEllipses = 0;
    var oncepe = 0;
    var onceper = 0;
    var countellips = 0;

    for (el of w3vv_allPages) {
      if( el.className == 'ellipsis' )
       {
        countellips++;
      }
    }

  // The next for loop, get the PageNum BEFORE the ellipsis and the PageNum AFTER the ellipsis
  // It is used to fill all the empty array keys on next loop, so to maintain the pages order

   for (el of w3vv_allPages) {

      if( countellips > 1 && onceper == 1 ){
        isNextEllipses0=el.innerText;
        onceper=2;
       }

      if( el.className == 'ellipsis' )
       {
        if( countellips > 1 && oncepe == 3 ){
           isPrevEllipses0 = w3vv_loadedPages_Aryxfl[isPrevEllips];
            onceper=1;
        }

        isPrevEllipses = w3vv_loadedPages_Aryxfl[isPrevEllips];
        oncepe=1;
       }

       w3vv_loadedPages_Aryxfl[el.innerText] = el.innerText;
       isPrevEllips = el.innerText;
       if( oncepe == 2 )
       { isNextEllipses = el.innerText; }

       oncepe++;
   }

alert('end isPrevEllipses0 '+isPrevEllipses0);
alert('end isNextEllipses0 '+isNextEllipses0);
alert('end isPrevEllipses '+isPrevEllipses);
alert('end isNextEllips '+isNextEllipses);


    var isPrevVal = 0;
    var isElli = 0;
    for (el of w3vv_allPages) {
    // !! classes NEED to MATCH on pagination li .elements
    //if( el.className != 'ellipsis' && el.className != 'dropdown-container' && el.className != 'arrow previous' && el.className != 'arrow next' )
     if( el.className != 'dropdown-container' && el.className != 'arrow previous' && el.className != 'arrow next' )
     {

       if( el.className == 'ellipsis' )
       {

        if(countellips < 2){
        for(let i=parseInt(isPrevEllipses)+1;i< parseInt(isNextEllipses);i++)
         {
          w3vv_pagesUrlAry[i] = i;
         }
        }

       else {

        if(isElli == 0){ // first ellipsis: add in between lacking keys
         for(let i=parseInt(isPrevEllipses0)+1;i< parseInt(isNextEllipses0);i++)
         {
          w3vv_pagesUrlAry[i] = i;
         }
         isElli = 1;
        }

        if(isElli == 1){ // second ellipsis: add in between lacking keys
          for(let i=parseInt(isPrevEllipses);i< parseInt(isNextEllipses);i++)
          {
           w3vv_pagesUrlAry[i] = i;
          }
          isElli = 2;
         }

       }
      } // END if( el.className == 'ellipsis' )


        if(el.className == 'active'){
         w3vv_pagesUrlAry[el.innerText] = el.baseURI;
        } else {  w3vv_pagesUrlAry[el.innerText] = el.firstChild.href; }


        //alert(w3vv_pagesUrlAry);
        //console.log(w3vv_loadedPages_Ary);

     }
    } // END for

  w3vv_pagesUrlAry = w3vv_pagesUrlAry.filter(n => n); // remove empty keys
  w3vv_PagesCount = w3vv_pagesUrlAry.length-1; //*

  console.log('w3vv_pagesUrlAry -> '+w3vv_pagesUrlAry);

    // So we'll have
    // w3vv_pagesUrlAry[1] PAGE1url, w3vv_pagesUrlAry[2] PAGE2url ... ect

  // *!! NEED to MATCH on DOM
  // Without this, if no other method provided, the flow cannot go on
  // The resulting w3vv_pagesUrlAry array need to contain all pages URL/NumPage pairs
  // Array Keys need to match the Page Number

// prepApp -> 'prepend' or 'append'
function w3vv_getPosts_pushT(prepApp, pageUrl, postID)
 {

   var XHR = new XMLHttpRequest();
   var urlEncodedData = "";
   var urlEncodedDataPairs = [];

   urlEncodedDataPairs.push(encodeURIComponent("w3vvVTLoadTopic") + "=1");

   XHR.addEventListener("error", function(event) {
    alert("Error: " + event);
   });
   XHR.addEventListener("timeout", function(event) {
    alert("Error: timeout");
   });
   XHR.onreadystatechange = function() {
    if (XHR.readyState === 0 || XHR.readyState === 1) {
     //console.log("XHRstarting");
    } else if (XHR.readyState === 3) {
      //console.log("XHRwaiting");
    } else if (XHR.readyState === 4) { // onload

    let Str = XHR.response;
   // console.log("RESP -> "+res);
    let c0 = Str.split('<body id="w3all_phpBB-vv_2-0-2-3_vscroll">')// split the body (with an unique id value: we hope that this sequence never will appear on topic's text!)

    const res = c0[1].slice(0,-15);

    // -> Append to the last post id
     if( prepApp == 'append' ){
       alert('Appending page '+ pageUrl);
      //var wh = document.getElementById("p"+postID);
      var wh = document.getElementById(postID);
      wh.insertAdjacentHTML("afterend", res.trim());
      oneiter=0;

     }

    // -> Prepend to the first post id
     if( prepApp == 'prepend' ){
      alert('prepending page '+ pageUrl);
      //var wh = document.getElementById("p"+postID);
      var wh = document.getElementById(postID);
      wh.insertAdjacentHTML("beforebegin", res.trim());
      // todo: would be better to jump to the last inserted pid, not to first of previous
      document.getElementById(postID).scrollIntoView();
      oneiter=0;
     }

      }
   }

   XHR.open("POST", pageUrl);
   XHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   XHR.send(urlEncodedDataPairs);

 } // END w3vv_getPosts_push


// check if element is visible after scrolling

  function w3vv_isScrolledIntoView(el) {
    var rect = el.getBoundingClientRect();
    var elemTop = rect.top;
    var elemBottom = rect.bottom;
    // Only completely visible elements return true:
    var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
    // Partially visible elements return true, in this way:
    //var isVisible = elemTop < window.innerHeight && elemBottom >= 0;
    return isVisible;
  } // END // w3vv_isScrolledIntoView()


  // The Listener


      var pageprev = parseInt(w3vv_startPage) - 1;
      var pagenext = parseInt(w3vv_startPage) + 1;
      var oneiter = 0;

      var elemPidFL = document.querySelectorAll("#page-body > .post");
      var lst = elemPidFL.length-1;

      var elemPidFirst = document.getElementById(elemPidFL[0].id);
      var elemPidLast  = document.getElementById(elemPidFL[lst].id);

      var firstPostId = elemPidFL[0].id;
      var lastPostId = elemPidFL[lst].id;


  document.addEventListener("scroll", (event) => {


      elemPidFL = document.querySelectorAll("#page-body > .post");
      lst = elemPidFL.length-1;

      //elemPidLast = n;
      //let lpid = lt[n].id.substring(1); // remove the p char that prepend the post id
      elemPidFirst = document.getElementById(elemPidFL[0].id);
      elemPidLast  = document.getElementById(elemPidFL[lst].id);

      firstPostId = elemPidFL[0].id;
      lastPostId = elemPidFL[lst].id;

      //var elemPidFirst = document.getElementById('p'+W3VVPP_VT_PLIST_PID_ARYFIRST);
      //var elemPidLast  = document.getElementById('p'+W3VVPP_VT_PLIST_PID_ARYLAST);

   if (!w3vv_ticking){
     window.requestAnimationFrame(() => {

  //alert(elemPidFirst.id);

//alert('test w3vv_startPage '+W3VVPP_VT_REV_PLIST_PID_ARYLAST);

            if(w3vv_isScrolledIntoView(elemPidFirst)){

//alert('actual elemPidFirst + oneiter' + elemPidFirst + ' - ' +oneiter);
//alert('actual FIRST POST ID ' + firstPostId);
//console.log('w3vv_loadedPages_Ary '+w3vv_loadedPages_Ary);
//console.log('w3vv_pagesUrlAry '+w3vv_pagesUrlAry);

     // Since the w3vv_pagesUrlAry, when was lacking of keys, has been filled with
     // numbers (pages numbers) as values, and not links, we can easily check when we reach the very last page
     // when/if in between ellipsis, just looking for the w3vv_pagesUrlAry[pageprev] value.
     // If it is a number we do not follow, because we reached the limit of pages to display.
     // Would be awesome to have all pushed keys (pages) with respective href? It can be done!

            if( isNaN(w3vv_pagesUrlAry[pageprev]) && oneiter == 0 && pageprev > 0 && w3vv_loadedPages_Ary[pageprev] === undefined && w3vv_pagesUrlAry[pageprev] != undefined ){
             //alert('line 224 firstPostId '+firstPostId);
             w3vv_getPosts_pushT('prepend', w3vv_pagesUrlAry[pageprev], firstPostId);
             w3vv_loadedPages_Ary[pageprev] = pageprev;
             //w3vv_loadedPages_Ary = w3vv_loadedPages_Ary.filter(Boolean); // remove empty keys
             pageprev--;
             oneiter = 1;
              // for (el of w3vv_loadedPages_Ary) {
                // console.log('w3vv_loadedPages_Ary - '+w3vv_loadedPages_Ary);
                 //alert('line 246 - pageprev '+pageprev);
              //}
            }
          }

          if(w3vv_isScrolledIntoView(elemPidLast)){

            //w3vv_loadedPages_Ary[pagenext] = w3vv_pagesUrlAry[pagenext]; // not good if exists more pages than on pagination
             // it is needed just the key
            if( isNaN(w3vv_pagesUrlAry[pagenext]) && oneiter == 0 && w3vv_loadedPages_Ary[pagenext] === undefined && w3vv_pagesUrlAry[pagenext] != undefined ){
             w3vv_getPosts_pushT('append', w3vv_pagesUrlAry[pagenext], lastPostId);
             w3vv_loadedPages_Ary[pagenext] = pagenext;
             //w3vv_loadedPages_Ary = w3vv_loadedPages_Ary.filter(Boolean); // remove empty keys
             pagenext++;
             oneiter = 1;
              // for (el of w3vv_loadedPages_Ary) {
                 console.log('w3vv_isScrolledIntoView(elemPidLast) -> w3vv_loadedPages_Ary '+w3vv_loadedPages_Ary);
              //}
            }
          }


       w3vv_ticking = false;

     }); // END window.requestAnimationFrame(() => {
   }
    w3vv_ticking = true;

  }); // END document.addEventListener("scroll"



 } // END //  if ( document.querySelector(".bar-top > .pagination > ul > li > span") != null )

</script>
<!-- ENDIF -->
