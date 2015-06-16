class ApplicationController < ActionController::Base
  # Prevent CSRF attacks by raising an exception.
  # For APIs, you may want to use :null_session instead.
  protect_from_forgery with: :exception

  before_action :configure_permitted_parameters, if: :devise_controller?
  before_action :check_for_unverified_account, if: :user_signed_in?

  protected

    def check_for_unverified_account
      if !current_user.confirmed_at
        flash.now[:alert] = 'You have not activated your account yet!'
      end
    end

    def configure_permitted_parameters
      devise_parameter_sanitizer.for(:sign_up) { |u| u.permit(:username, :email, :password, :password_confirmation, :remember_me) }
      devise_parameter_sanitizer.for(:account_update) { |u| u.permit(:username, :email, :password, :password_confirmation, :current_password) }
    end

    def require_sign_in
      if !user_signed_in?
        redirect_to new_user_session_path, alert: "You must be signed in to view that page!"
      end
    end
end
