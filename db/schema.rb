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

ActiveRecord::Schema.define(version: 20150615011043) do

  create_table "levels", primary_key: "level_id", force: :cascade do |t|
    t.string  "name"
    t.boolean "is_admin",     default: false
    t.boolean "is_moderator", default: false
  end

  create_table "profile_data", force: :cascade do |t|
    t.string  "hometown"
    t.date    "birthday"
    t.string  "occupation"
    t.string  "website"
    t.text    "interests"
    t.integer "first_park"
    t.integer "home_park"
    t.integer "favorite_park"
    t.integer "first_coaster"
    t.integer "favorite_coaster"
    t.integer "favorite_steel_coaster"
    t.integer "favorite_wood_coaster"
    t.integer "favorite_twisted_coaster"
    t.integer "favorite_out_and_back_coaster"
    t.text    "extra_data"
    t.integer "user_id"
  end

  add_index "profile_data", ["user_id"], name: "index_profile_data_on_user_id"

  create_table "users", primary_key: "user_id", force: :cascade do |t|
    t.string   "email",                  default: "", null: false
    t.string   "encrypted_password",     default: "", null: false
    t.string   "reset_password_token"
    t.datetime "reset_password_sent_at"
    t.datetime "remember_created_at"
    t.integer  "sign_in_count",          default: 0,  null: false
    t.datetime "current_sign_in_at"
    t.datetime "last_sign_in_at"
    t.string   "current_sign_in_ip"
    t.string   "last_sign_in_ip"
    t.datetime "created_at",                          null: false
    t.datetime "updated_at",                          null: false
    t.string   "username"
    t.integer  "level_id",               default: 4
  end

  add_index "users", ["email"], name: "index_users_on_email", unique: true
  add_index "users", ["reset_password_token"], name: "index_users_on_reset_password_token", unique: true

end
