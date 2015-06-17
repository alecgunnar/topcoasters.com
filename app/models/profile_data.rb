class ProfileData < ActiveRecord::Base
  belongs_to :user
  validates_date :birthday, :on_or_before => 13.years.ago, :allow_nil => true
end
