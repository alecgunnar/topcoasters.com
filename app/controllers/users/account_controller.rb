class Users::AccountController < DeviseController
  before_action :require_sign_in

  layout 'account'

  def main

  end

  def profile_data
    if request.patch?
      current_user.profile_data.update profile_data_params
      redirect_to settings_profile_data_path, notice: 'Your profile information has been saved.'
    end
  end

  private

  def profile_data_params
    params.require(:profile_data).permit(:hometown, :occupation, :birthday, :website, :interests)
  end
end
