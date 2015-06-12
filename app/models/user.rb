require 'digest/md5'

class User < ActiveRecord::Base
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :trackable, :validatable

  validates :username, :presence => true, :uniqueness => true

  def profile_pic_url
    hash = Digest::MD5.hexdigest(self.email)
    "http://www.gravatar.com/avatar/#{hash}"
  end
end
