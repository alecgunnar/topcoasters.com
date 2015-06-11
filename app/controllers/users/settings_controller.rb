class Users::SettingsController < DeviseController
  before_action :require_sign_in

  layout 'settings'

  def main

  end
end
