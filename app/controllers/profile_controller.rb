class ProfileController < ApplicationController
  before_action :require_sign_in

   def show
    @user = current_user
  end
end
