function class_form(form, course) {
			if (form.course.value != 0) {
					form.submit();
					return true;
				}
			else {
				alert("Please select a course from the menu");
				return false;
			}
		}