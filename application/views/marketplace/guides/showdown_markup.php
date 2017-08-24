<h1>Markup Syntax</h1>
<p>For certain elements we support a <a href="https://github.com/showdownjs/showdown">showdown markup language</a> for a richer experience.</p>

<h3>Headings</h3>
<p>You can use three sizes of headers using #s.</p>
<p class="mono">
# Large Header<br />
## Medium Header<br />
### Small Header
</p>


<h3>Bold, Italic & Strikethrough</h3>
<p class="mono">
*This text will be italic*<br />
**This text will be bold**<br />
a ~~strikethrough~~ element
</p>
<p>Bold and italic can use either a * or an _ around the text for styling. This allows you to combine both bold and italic if needed.</p>
<p class="mono">
**Everyone _must_ attend the meeting at 5 o'clock today.**
</p>


<h3>Links</h3>
<p>If you wrap a valid URL or email in <> it will be turned into a link whose text is the link itself.</p>
<p class="mono">
link to http://www.google.com/<br />
this is my email somedude@mail.com
</p>



<h3>Images</h3>
<p>You can display images using the following format, and optionally, define its size and title:</p>
<p class="mono">
![Alt text](url/to/image)<br />
![Alt text](url/to/image "Optional title")<br />
![Alt text](url/to/image =250x250 "Optional title")
</p>


<h3>Youtube Videos (Coming Soon)</h3>
<p>Embed a YouTube video using the following format:</p>
<p class="mono">
![Video Title](https://www.youtube.com/watch?v=H_j3x8N8fpA)<br />
![Video Title](https://www.youtube.com/watch?v=H_j3x8N8fpA?start=350&end=389)<br />
</p>



<h3>Unordered Lists</h3>
<p>You can make an unordered list by preceding list items with either a *, a - or a +. Markers are interchangeable too.</p>
<p class="mono">
* Item<br />
+ Item<br />
- Item
</p>

<h3>Ordered Lists</h3>
<p>You can make an ordered list by preceding list items with a number.</p>
<p class="mono">
1. Item one<br />
2. Item two<br />
3. Item three
</p>


<h3>Nested Lists</h3>
<p>You can create nested lists by indenting list items by <b><i>four spaces</i></b>.</p>
<p class="mono">
1.  Item 1<br />
&nbsp;&nbsp;&nbsp;&nbsp;1. A corollary to the above item.<br />
&nbsp;&nbsp;&nbsp;&nbsp;2. Yet another point to consider.<br />
2.  Item 2<br />
&nbsp;&nbsp;&nbsp;&nbsp;* A corollary that does not need to be ordered.<br />
&nbsp;&nbsp;&nbsp;&nbsp;* This is indented four spaces<br />
&nbsp;&nbsp;&nbsp;&nbsp;* You might want to consider making a new list.<br />
3.  Item 3
</p>




<h3>Blockquotes</h3>
<p>You can indicate blockquotes with a >.</p>
<p class="mono">
In the words of Abraham Lincoln:<br />
> Pardon my french
</p>
<p>Blockquotes can have multiple paragraphs and can have other block elements inside.</p>
<p class="mono">
> A paragraph of text<br />
><br />
> Another paragraph<br />
><br />
> - A list<br />
> - with items
</p>




<h3>Tables</h3>
<p>Colons can be used to align columns. You don't need to make the raw Markdown line up prettily. You can also use other markdown syntax inside them.</p>
<p class="mono">
|&nbsp;Tables&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;Are&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;Cool&nbsp;&nbsp;|<br />
|&nbsp;-------------&nbsp;|:-------------:|&nbsp;-----:|<br />
|&nbsp;**col&nbsp;3&nbsp;is**&nbsp;&nbsp;|&nbsp;right-aligned&nbsp;|&nbsp;$1600&nbsp;|<br />
|&nbsp;col&nbsp;2&nbsp;is&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;*centered*&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;$12&nbsp;|<br />
|&nbsp;zebra&nbsp;stripes&nbsp;|&nbsp;~~are&nbsp;neat~~&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;$1&nbsp;|
</p>
