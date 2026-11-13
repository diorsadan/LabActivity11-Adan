# Lab 11 Defense Notes – Barangay Bulletin (Purok Adan)

## Eloquent / queries

**1. What is the N+1 problem?**
1 query to get the posts, then 1 more query for *each* post's author. With 6 posts per page that's 1 + 6 = 7 queries just for authors. It gets worse the more posts there are.

**2. How did you fix N+1 in this project?**
Eager loading. In `index()` I used `->with(['author', 'tags'])`, so Laravel gets all the authors in 1 query and all the tags in 1 query no matter how many posts there are. My `/posts` page stays at the same number of queries per page.

**3. What's the difference between `with()` and `load()`?**
Both are eager loading. `with()` is used *while building the query*, before `->get()`/`->paginate()`. `load()` is used *after* I already have the model. In `show()` I first need `findOrFail()` and `views++`, so I used `$post->load([...])` after.

**4. What does `Model::preventLazyLoading()` do?**
It throws an error if I forget eager loading and access a relationship inside a loop. So I find N+1 right away while coding. It's only on when not in production (`! app()->isProduction()`), so real users never see that error.

**5. Why `withCount('comments')` instead of `$post->comments->count()`?**
`withCount` adds a `comments_count` column using a COUNT in SQL. It doesn't load every comment just to count them. The other way would load all the comments (or cause N+1).

## Relationships

**6. What's the difference between `attach()` and `sync()`?**
`attach()` adds rows to the pivot table on top of what's already there. `sync()` makes the tags *exactly* the list I give it and removes the others. In TagSeeder I attach random tags to every post, then use `sync()` on the pinned post so it only has Purok Adan and Urgent.

**7. Why is the pivot table called `post_tag`?**
Laravel's naming rule: both model names singular, in alphabetical order, joined with `_`. "post" comes before "tag", so it's `post_tag`. Because I followed the rule, `belongsToMany(Tag::class)` finds the table by itself.

**8. Why is the primary key `['post_id', 'tag_id']`?**
So the same tag can't be attached to the same post twice. The pair has to be unique.

**9. What is `hasManyThrough`?**
Getting records 2 tables away. An author doesn't have `author_id` on comments, but: authors → posts (`author_id`) → comments (`post_id`). So `$author->comments()` gets all comments on all their posts. I used it for "N comments received" on the author page.

**10. Why is `User::author()` hasOne but `Author::user()` belongsTo?**
It depends on which table has the foreign key. `user_id` is in the **authors** table, so Author `belongsTo` User and User `hasOne` Author. It's one-to-one because I made `user_id` unique.

**11. What is `$fillable` for?**
Protection from mass assignment. Only the columns in `$fillable` can be set with `create()`/`update()`. If someone adds an extra field to the form (like `is_pinned`), it's ignored. Without `$fillable`, `create()` throws `MassAssignmentException`.

## Form Requests / validation

**12. In a Form Request, what's `authorize()` vs `rules()`?**
`authorize()` = *is this user allowed?* If it returns false → 403. `rules()` = *is the data valid?* If it fails → go back with errors. `authorize()` runs first. In `StorePostRequest`, authorize checks that the user has an author row, so Lola (resident) gets 403.

**13. Why use a Form Request instead of `$request->validate()`?**
It keeps the controller short and puts all the validation and permission logic in one class. Laravel runs it automatically before the controller method, so if the controller code runs, the data is already valid. Then I use `$request->validated()`/`safe()` so only validated fields are saved, never `$request->all()`.

## HTTP errors

**14. What do 403, 404, 419 and 422 mean in this app?**
- **403 Forbidden** – logged in but not allowed. Ex: Lola opening `/posts/create`.
- **404 Not Found** – the record doesn't exist. Ex: a draft post (`findOrFail`) or `/authors/999`.
- **419 Page Expired** – missing or wrong CSRF token. Ex: a form without `@csrf`.
- **422 Unprocessable** – validation failed on a JSON/AJAX request. For my normal HTML forms, Laravel redirects back (302) with `$errors` instead.

**15. What does `@csrf` do?**
It adds a hidden `_token` input to the form. Laravel checks that the token matches the user's session, so another website can't secretly submit forms (like logout or posting a comment) as my user. No token → 419.

**16. What does `old()` do?**
When validation fails, Laravel sends back the old input. `old('title')` puts it back in the field so the user doesn't have to type everything again. For checkboxes I used `@checked(in_array($tag->id, old('tags', [])))`, and for the select `@selected(old('category') == $category)`.

## Migrations / routes / security

**17. What does `migrate:rollback` do, and why does `down()` matter?**
Rollback runs the `down()` of the last batch of migrations to undo them. So `down()` must fully reverse `up()`. In `add_user_id_to_authors_table` I drop the foreign key, then the unique index, then the column (SQLite won't drop a column that still has an index).

**18. Why did you add a new migration instead of editing the Lab 10 authors migration?**
The Lab 10 migration already ran. Editing it won't change an existing database, and teammates or a server would end up with different tables. A new migration is a record of the change.

**19. Why is `/posts/create` above `/posts/{id}` in web.php?**
Routes are checked from top to bottom. If `{id}` came first, "create" would be treated as an id, `show('create')` would run and give 404. Putting the fixed URL first fixes it.

**20. Why does `user_id` come from `auth()->id()` and not the form?**
Anything in a form can be edited in the browser's dev tools. If `user_id` came from the form, someone could post a comment as another user. `auth()->id()` comes from the logged-in session, so it can't be faked. Same reason posts are created through `auth()->user()->author->posts()->create()`.
