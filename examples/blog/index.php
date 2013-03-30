t: 'textarea', dir: 'data/', ext: '.txt', f: Request.form, ? Request.method = {
    "POST": File(dir (f.slug) ext).write(f.text), Router.redirect('?')}*/
'<h1>My SimplifiedPHP Blog<h1><form action="?"><h5>New Post:</h5>Post slug:
 <input name="slug" /><br/><'t' name="text" cols=60 rows=20></'t'><br/>
 <input type="submit" /></form><hr/>'.print, slug: Request.args.slug, ? slug = {
    Void: Directory(dir).each{'<a href="?slug=' Path(it).name '">' \
          File(it).string.lines[0] '</a><br/>'.print}
       *: {'<a href="?">&lauqo; All Posts</a>'.print
          File(dir post ext).string.lines.each ? key = {0: '<h2>' it '</h2>'
                                                        *:  '<p>' it '</p>'}}}