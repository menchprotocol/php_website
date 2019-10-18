

<script src="/js/lib/rangy/rangy-core.js" type="text/javascript"></script>
<script src="/js/lib/rangy/rangy-classapplier.js" type="text/javascript"></script>
<script src="/js/lib/undo.js" type="text/javascript"></script>
<script src="/js/lib/medium.js" type="text/javascript"></script>

<link href="/css/lib/medium.css" rel="stylesheet"/>




<div class="container">

    <h1 class="hi">Hi</h1>

    <div id="MediumEditor">
        <p>starting point.</p>
        <p>Nice I like it @1234</p>
        <span><a href="/">nice</a></span>
    </div>

    <hr />

</div>

<script>
    new Medium({
        element: document.getElementById('MediumEditor'),
        maxLength:2000,
        mode: Medium.partialMode,
        autoHR: false,
        autofocus: true,
        placeholder: "Your Story",
        cssClasses: {
            editor: 'Medium',
            pasteHook: 'Medium-paste-hook',
            placeholder: 'Medium-placeholder',
            clear: 'Medium-clear'
        },
        beforeInvokeElement: function () {
            alert('hi');
        }
    });

    $(document).ready(function () {

        $("#MediumEditor p").click(function() {
            console.log($(this));
        });


        $("#MediumEditor p").focusout(function() {
            alert('outfocus');
        });

    });


</script>