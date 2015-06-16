# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)

admin_level = Level.create name: 'Administrator', is_admin: true, is_moderator: true
Level.create name: 'Moderator', is_moderator: true
Level.create name: 'Contributor'
Level.create name: 'Member', is_default: true

root                       = User.new
root.username              = 'Root'
root.email                 = 'root@domain.com'
root.level_id              = admin_level.level_id
root.password              = '$iamroot'
root.password_confirmation = '$iamroot'
root.skip_confirmation!
root.save!