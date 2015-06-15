require 'digest/md5'

class User < ActiveRecord::Base
  has_one :profile_data, dependent: :destroy
  belongs_to :level
  after_create :after_create_handler

  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :trackable, :validatable

  validates :username, :presence => true, :uniqueness => true

  def profile_pic_url
    hash = Digest::MD5.hexdigest(self.email)
    "http://www.gravatar.com/avatar/#{hash}"
  end

  def after_create_handler
    self.create_profile_data user_id: self.user_id
  end
end
