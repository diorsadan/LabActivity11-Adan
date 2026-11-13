# LabActivity11-Adan – Barangay Bulletin (Purok Adan)

Lab Activity 11 for 4-334 Full-Stack Web Development.
Made by **Dion Ren Adan**.

This continues my Lab 10 Barangay Bulletin. Now posts show the author's name and tags,
logged-in residents can comment, and the Kagawad can create new posts from the site.
All forms are validated using Form Requests.

## Seeded logins

Password for both: `bulletin2026`

| Name | Email | What they can do |
|---|---|---|
| Lola Adan | lola.adan@bulletin.test | resident – can comment only |
| Kagawad Dion Ren Adan | kagawad.adan@bulletin.test | has an author profile – can comment and create posts |

There are also 8 random resident users made by the factory.

## Tags

Purok Adan, Urgent, Libre, Schedule, Bakuna (Bakuna is my own tag)

The pinned post "Purok Adan Clean-up Drive" always has Purok Adan and Urgent.

## Relationships I used

- **User hasOne Author / Author belongsTo User** – one login account can have at most one
  author profile. I added a nullable `user_id` to authors because not every author needs a login,
  and residents like Lola don't have an author profile. This is also how I check who can post.
- **Author hasMany Post / Post belongsTo Author** – one Kagawad can write many posts, but each
  post only has one author (`author_id` on posts).
- **Post hasMany Comment / Comment belongsTo Post** – a post can have many comments but a comment
  is only for one post.
- **User hasMany Comment / Comment belongsTo User** – a user can comment many times, and each
  comment needs to know who wrote it so I can show the name.
- **Post belongsToMany Tag** – one post can have many tags and one tag can be on many posts, so
  I needed the pivot table `post_tag`. Tags are added with `attach()` and `sync()`.
- **Author hasManyThrough Comment (through Post)** – comments are not directly linked to authors,
  only to posts. hasManyThrough lets me count all comments on an author's posts for the
  "N comments received" on the author page.

I also turned on `Model::preventLazyLoading()` so the app throws an error if I forget to eager
load something (N+1 problem). The list page uses `with(['author','tags'])` and
`withCount('comments')`, and the show page uses `load()` for the comments and their users.

## Validation

- **StoreCommentRequest** – must be logged in, comment is required, 3 to 1000 characters.
  Custom message: "Purok Adan comments need at least 3 characters."
- **StorePostRequest** – only users with an author profile can post (Lola gets 403).
  Checks title, body (min 20), category (my 5 categories), and at least 1 tag.

## How to run

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run dev
php artisan serve

Then open http://127.0.0.1:8000