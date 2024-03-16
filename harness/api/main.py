# This is an example of the script to be added to the Ada Build Colab notebooks.
# The requests library is already available with no need to install it. For the
# public-facing site, the `verify` parameter is not needed. The lesson_id will
# be set to the lesson slug the code is placed in. This will be located at the
# end of each lesson notebook. The learner will receive their own id after
# registering and will input that themselves using the form prompt colab
# capabilities. This endpoint activates the same completion workflow as when
# triggered through the progress interface.

VALID_LEARNER = "9qMCplSZkn"  # paste a valid learner slug from the db
VALID_LESSON = "6MGGGrMgkq"  # paste a valid lesson slug from the db
INVALID_LEARNER = "invalid_learner"
INVALID_LESSON = "invalid_lesson"

# @title Usage Logging
learner_id = INVALID_LEARNER # @param {type:"string"}
lesson_id = VALID_LESSON
from requests import post

base_url = "https://sample-wp.local/wp-json/ada-aba/v1/completion"

response = post(base_url, json=dict(
    u=learner_id,
    lesson=lesson_id),
    verify='sample-wp-local-chain.pem')  # replace with your own pem file

# if response.status_code != 200:
#     print(f"Error: {response.status_code}")
#     print(response.json())
#     exit(1)

# print(response.json())

if response.status_code != 200:
    print("There was an error completing the lesson. Confirm your learner id"
          " and try again later.")
else:
    print("The lesson is being marked complete. Please check your email to confirm"
          " this action.")
