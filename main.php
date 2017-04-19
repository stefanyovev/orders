<?php

      mysql_connect( 'localhost' , '' , '' )
      
         or exit( mysql_error() );
         
      mysql_query( 'set names utf8' );
      
      function insert( $args ){ mysql_query( "insert $args" ); echo mysql_error(); }
      function update( $args ){ mysql_query( "update $args" ); echo mysql_error(); }
      function delete( $args ){ mysql_query( "delete $args" ); echo mysql_error(); }
      function select( $args ){
         if( !( $x = mysql_query( "select $args" ) ) ) return false;
         if( !( $y = mysql_num_rows($x) ) ) return false;
         while( $y = mysql_fetch_array($x) ) $z[] = $y;
         mysql_free_result( $x ); return $z; }   

      session_start();
      
      $sess = & $_SESSION;
      $env = & $_SERVER;      
      $loc = trim( str_replace( "?{$env[QUERY_STRING]}", '', $env[REQUEST_URI] ), '/' ); $loc = $loc ? explode( '/', $loc ) : false;
      $post = $env[REQUEST_METHOD] == 'POST';
      $data = empty( $_POST ) ? false : ( get_magic_quotes_gpc() ? array_map( 'stripslashes', $_POST ) : $_POST  );

      $short = strstr( $env[HTTP_ACCEPT], 'text/plain' ) === false ? false : true;

      header( 'Cache-control: no-cache, no-store, must-revalidate' );
      header( 'Content-type: text/' . ( $short ? 'plain' : 'html' ) . '; charset=UTF-8' );

?><!doctype html>
<html lang="en">
   <head>
      <title> StefanYovev </title>
      <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
      <style type="text/css">
      
         body, body * {
            overflow: hidden;
            box-sizing: border-box; 
            margin: 0; padding: 0; 
            top: 0; left: 0; right: 0; bottom: 0;
            border: none; outline: none;
            user-select: none;
            cursor: default; /* -webkit-grab, -webkit-grabbing */
            text-align: center;
            text-decoration: none;
            font-size: 1em;
            font-weight: normal;
            line-height: 1em;
            letter-spacing: -0.01em;
            background-position: center center;
            background-repeat: no-repeat;
            background-color: transparent }

         body {
            position: fixed;
            perspective: 150vw }
         
         body * {
            display: inline-block;
            vertical-align: middle;
            position: relative;
            cursor: inherit;
            transform: translateZ(0);
            transition: all 100ms }
            
         /*------------------------------ ELEMENTS --- */
         
         a, button, input[type="submit"], input[type="reset"] {
            cursor: pointer !important }

         input[type="text"], input[type="password"], textarea {
            cursor: text !important }
         
         h1 { font-size: 2em }
         h2 { font-size: 1.5em }
         h3 { font-size: 1.25em }
         h4 { font-size: 1.125em }
         h5 { font-size: 1.0625em }
         h6 { font-size: 1.03125em }
         h7 { font-size: 1.015625em }
         
         table           { display: inline-table }
         tr              { display: table-row }
         thead           { display: table-header-group }
         tbody           { display: table-row-group }
         tfoot           { display: table-footer-group }
         col             { display: table-column }
         colgroup        { display: table-column-group }
         td, th          { display: table-cell }
         caption         { display: table-caption }
         td, th, tr      { vertical-align: inherit }

         /*------------------------------ modifiers */
         
         .wide {
            width: 100% }
         .tall {
            height: 100% }
            
         .left {
            text-align: left;
            margin-left: 0 !important;
            transform-origin: left }
         .right {
            text-align: right;
            margin-right: 0 !important;
            transform-origin: right }
         .top {
            vertical-align: top;
            margin-top: 0 !important;
            transform-origin: top }
         .top.right { transform-origin: top right }
         .bottom {
            vertical-align: bottom;
            margin-bottom: 0 !important;
            transform-origin: bottom }
                        
         .z0 { z-index: 0 }
         .z1 { z-index: 1 }
         
         .s0 { width: 0; height: 0 }
         .s1 { width: 1em; height: 1em }
            
         .c0 { background-color: rgb( 230, 230, 230 ) }
         .c1 { background-color: rgba( 255, 255, 255, .99 ) }
            
         .sh0 { box-shadow: inset 0 0 0.4em 0 rgba( 0, 0, 0, .16 ) }
         .sh1 { box-shadow: 0 0 .438em 0 rgba( 0, 0, 0, .16 ) }

         /*------------------------------------------------- X --- ;
           more divs = horizontal slide; too much content in a div = vertical slide (scroll)
           -----------------------------------------------------------------------  */
         
         .x {
            white-space: nowrap }

         .x > span {
            width: 0;
            height: 100% }
            
         .x > div {
            white-space: normal }

         .z {
            position: absolute;
            margin: auto }
            
         .z.x {
            pointer-events: none }
         .z.x > div {
            pointer-events: all }
            
         /*------------------------------ user */
                  
         form, h1, h2, table, th, input, select, button {
            padding: 0.2em; }
         
         input, select, th, button {
            border-bottom: 1px dotted gray }

         .z.x {
            background-color: rgba( 128, 128, 128, 0.2 ) }

         body .sh0:hover {
            background-color: rgba(192,192,128, .1 ) }
            
         body .sh1:hover {
            background-color: rgba(250,245,250, 1 ) }

         body *:focus {
            outline: 1px solid red }
          
         

      </style>
      <script type="text/javascript">
      
         win = window;
         doc = document;
         con = console; function log(x){con.log(x)}
         
         function clas( x, y ){ return x.getElementsByClassName( y ) }
         function id( x, y ){ return x.getElementById( y ) }
         
         function loop( x, f ) { for( var i = 0, l = x.length; i < l; i++ ) f.call( x[i] ) }
         
         function is( x, y ){ return x.classList.contains( y ) }
         function set( x, y ){ x.classList.add( y ) }
         function unset( x, y ){ x.classList.remove( y ) }

         function make( x, y, z, u ){
            y = doc.createElement( y );
            if( x ) y.id = x;
            if( z ) y.className = z;
            if( u ) y.style.cssText += u;
            return y; }

         function bind( x, e, h ){
            return x.addEventListener( e, function( ev ){
               var initiator = ev.target, ex = ev.clientX, ey = ev.clientY;
               h.call( x, ex, ey, initiator ); })}

         function call( x, data, callback ){
            r = win.XMLHttpRequest ? ( new XMLHttpRequest() ) : ( new ActiveXObject( 'Microsoft.XMLHTTP' ) );
            if( callback ) r.onload = function(){ callback( r.responseText ) };
            r.open( data ? 'POST' : 'GET', '/' + x?x:'', true );
            r.setRequestHeader( 'Accept', 'text/plain' );
            r.send( data ); // timeout ?
            return r; }
         
         bind( win, 'load', function(){
         
            body = doc.body;
            forms = doc.forms;

         });

      </script>
   </head>
   <body class=" x c0 sh0 " ><span>&nbsp;</span><div class="">

         <form action="" class=" bottom c1 sh1">
            <h2 class="wide"> Search </h2>
            <br><br>
            <select name="time">
               <option value="0"> All Time </option>
               <option value="1"> Today </option>
               <option value="7"> Last 7 Days </option>
            </select>
            <input type="text" name="search" placeholder="Query" style="" >
            <br><br>
            <div class="wide right">
               <input type="submit" value=" Search ">
            </div>
         </form>

         <form name="add" action="" class="bottom c1 sh1" >
            <h2 class="wide" > Add </h2>
            <br><br>
            <label style="width: 35%"> user </label>
            <select style="width: 55%" name="user">
               <option value="a">a</option>
               <option value="a"> b </option>
               <option value="a"> cde </option>
            </select>
            <br>
            <label style="width: 35%"> product </label>
            <select style="width: 55%" name="product">
               <option value="">f</option>
            </select>
            <br>
            <label style="width: 35%" for="quantity">quantity</label>
            <input style="width: 55%" name="quantity" type="number" max="5" value="1">
            <br><br>
            <div class=" wide right ">
               <input type="submit" value=" Add ">
               <input type="reset" value=" Reset ">
            </div>
         </form>

         <h1 class="wide c1 sh1 z1"> Orders </h1>

         <table class="c1 sh1">
            <tr ><th>one</th><th>two</th><th>three</th></tr>
            <tr ><td>one</td><td>twssssssssssssssssssssssssssssssssssssssssssssssssssssso</td><td>three</td></tr>
            <tr ><td>one</td><td>two</td><td>three</td></tr>
         </table>

         <div class=" z z1 x " ><span>&nbsp;</span><div class="c1 sh1" >
         
            <form name="edit" action="" class="c1 sh1" >
               <h2 class="wide" > Edit </h2>
               <br><br>
               <label style="width: 35%"> user </label>
               <select style="width: 55%" name="user">
                  <option value="a">a</option>
                  <option value="a"> b </option>
                  <option value="a"> cde </option>
               </select>
               <br>
               <label style="width: 35%"> product </label>
               <select style="width: 55%" name="product">
                  <option value="">f</option>
               </select>
               <br>
               <label style="width: 35%" for="quantity">quantity</label>
               <input style="width: 55%" name="quantity" type="number" max="5" value="1">
               <br><br>
               <div class=" wide right ">
                  <input type="reset" value=" Reset ">
                  <input type="submit" value=" Save ">
                  <button onclick="clas(body,'z x')[0].style.display='none';return false"> Cancel </button>
               </div>
            </form>
            
         </div></div>

         <br><br>
                  
         <a href="#" > link </a> | <a href="#" > link </a>
               
      </div> 
   </body>
</html>
