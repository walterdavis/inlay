require 'nokogiri'
html = <<-END
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Hello, World</title>
</head>
  <body>
    <div>
      <h1>Hello, World</h1>
      <p>This is a test</p>
    </div>
  </body>
</html>
END
doc = Nokogiri::HTML::Document.parse(html)
head = doc.at_css('head')
puts head.path
