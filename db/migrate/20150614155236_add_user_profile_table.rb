class AddUserProfileTable < ActiveRecord::Migration
  def change
    create_table :profile_data do |t|
      t.string :hometown
      t.date :birthday
      t.string :occupation
      t.string :website
      t.text :interests
      t.integer :first_park
      t.integer :home_park
      t.integer :favorite_park
      t.integer :first_coaster
      t.integer :favorite_coaster
      t.integer :favorite_steel_coaster
      t.integer :favorite_wood_coaster
      t.integer :favorite_twisted_coaster
      t.integer :favorite_out_and_back_coaster
      t.text :extra_data
    end

    add_belongs_to :profile_data, :user, index: true, foreign_key: true

    User.all do |u|
      ProfileData.create user_id: u.id
    end
  end
end
