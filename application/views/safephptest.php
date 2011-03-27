<p>This next line should be unescaped:<p>
<p><%=='<h1>Test line</h1>'%></p>
<p>This next line should be escaped:</p>
<p><%='<h1>Test line</h1>'%></p>

<% if(microtime(true) % 2 == 0): %>
  <p style="color: blue;">This line will change color depending on the time</p>
<% else: %>
  <p style="color: red;">This line will change color depending on the time</p>
<% endif %>
