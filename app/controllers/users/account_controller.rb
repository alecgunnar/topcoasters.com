class Users::AccountController < DeviseController
  before_action :require_sign_in
  before_action :set_user_resource, :not => [:profile_data]

  layout 'account'

  def main

  end

  def profile_data
    self.resource = current_user.profile_data.clone

    if request.patch?
      resource.assign_attributes profile_data_params

      if resource.valid?
        resource.save!
        redirect_to profile_settings_path, notice: 'Your profile information has been saved.'
      end
    end
  end

  def change_username
    self.resource = current_user

    if request.patch?
      permitted = change_username_params

      if resource.valid_password? permitted[:password]
        resource.errors.add :password, 'is invalid.'
      end

      resource.assign_attributes :username => permitted[:username]

      if resource.valid? && resource.errors.empty?
        resource.save!
        redirect_to change_username_path, notice: 'Your username has been saved.'
      end
    end
  end

  private

    def set_user_resource
      self.resource = current_user
    end

    def profile_data_params
      params.require(:profile_data).permit(:hometown, :occupation, :birthday, :website, :interests)
    end

    def change_username_params
      params.require(:user).permit(:username, :password)
    end
end
