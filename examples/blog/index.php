/**
 * Configuration
 */
dir: 'data/'
ext: '.txt'

? Request.method = {
  "POST": {
    File(dir (Request.form.slug) ext).write(Request.form.text)
    Router.redirect('?')
  }
}

"""<h1>My SimplifiedPHP Blog</h1>
<form action="?">
  <h5>New Post:</h5>
  Post slug: <input name="slug" /><br/>
  <textarea name="text" cols=60 rows=20></textarea><br/>
  <input type="submit" />
</form>
<hr/>""".print

? Request.args.slug = {
  Void: {
    Directory(dir).each{
      '<a href="?slug=' Path(it).name '">' File(it).string.lines[0] '</a><br/>'.print
    }
  }

  *: {
    '<a href="?">&lauqo; All Posts</a>'.print

    File(dir post ext).string.lines.each ? key = {
      0: '<h2>' it '</h2>'.print
      *: '<p>' it '</p>'.print
    }
  }
}