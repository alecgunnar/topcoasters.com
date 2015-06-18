include Gravtastic

class User < ActiveRecord::Base
  has_one :profile_data, dependent: :destroy
  belongs_to :level
  after_create :after_create_handler

  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :trackable, :validatable, :confirmable

  validates :username, :presence => true, :uniqueness => true

  gravtastic :email, :secure => true, :default => :identicon

  private

    def after_create_handler
      self.create_profile_data user_id: self.user_id
    end
end
