# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20150615234419) do

  create_table "levels", force: :cascade do |t|
    t.string  "name",         limit: 255
    t.boolean "is_admin",     limit: 1,   default: false
    t.boolean "is_moderator", limit: 1,   default: false
    t.boolean "is_default",   limit: 1,   default: false
  end

  create_table "profile_data", force: :cascade do |t|
    t.string  "hometown",                      limit: 255
    t.date    "birthday"
    t.string  "occupation",                    limit: 255
    t.string  "website",                       limit: 255
    t.text    "interests",                     limit: 65535
    t.integer "first_park",                    limit: 4
    t.integer "home_park",                     limit: 4
    t.integer "favorite_park",                 limit: 4
    t.integer "first_coaster",                 limit: 4
    t.integer "favorite_coaster",              limit: 4
    t.integer "favorite_steel_coaster",        limit: 4
    t.integer "favorite_wood_coaster",         limit: 4
    t.integer "favorite_twisted_coaster",      limit: 4
    t.integer "favorite_out_and_back_coaster", limit: 4
    t.text    "extra_data",                    limit: 65535
    t.integer "user_id",                       limit: 4
  end

  add_index "profile_data", ["user_id"], name: "fk_rails_bf58cc58c6", using: :btree

  create_table "users", force: :cascade do |t|
    t.string   "email",                  limit: 255, default: "", null: false
    t.string   "encrypted_password",     limit: 255, default: "", null: false
    t.string   "reset_password_token",   limit: 255
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",          limit: 4,   default: 0,  null: false
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip",     limit: 255
    t.string   "last_sign_in_ip",        limit: 255
    t.datetime "created_at",                                      null: false
    t.datetime "updated_at",                                      null: false
    t.string   "username",               limit: 255
    t.integer  "level_id",               limit: 4,   default: 4,  null: false
    t.string   "confirmation_token",     limit: 255
    t.datetime "confirmed_at"
    t.datetime "confirmation_sent_at"
    t.string   "unconfirmed_email",      limit: 255
  end

  add_index "users", ["confirmation_token"], name: "index_users_on_confirmation_token", unique: true, using: :btree
  add_index "users", ["email"], name: "index_users_on_email", unique: true, using: :btree
  add_index "users", ["level_id"], name: "fk_rails_b4cec70de6", using: :btree
  add_index "users", ["reset_password_token"], name: "index_users_on_reset_password_token", unique: true, using: :btree

  add_foreign_key "profile_data", "users"
  add_foreign_key "users", "levels"
end
