class AddLevelsTable < ActiveRecord::Migration
  def change
    create_table :levels, primary_key: 'level_id' do |t|
      t.string :name
      t.boolean :is_admin, default: false
      t.boolean :is_moderator, default: false
      t.boolean :is_default, default: false
    end

    add_column :users, :level_id, :integer, default: 4, null: false
  end
end
