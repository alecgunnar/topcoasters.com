class Users::SettingsController < DeviseController
  before_action :require_sign_in

  layout 'account'

  def main

  end
end
