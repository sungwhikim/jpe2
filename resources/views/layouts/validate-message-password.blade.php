<!-- app/views/layouts/validate-message-password.blade.php -->

<p ng-message="required">This field is required</p>
<p ng-message="minlength">The password is too short. It must be at least 6 characters</p>
<p ng-message="maxlength">The password is too long.  It must be 30 characters or less.</p>
<p ng-message="compareTo">The passwords must match.</p>