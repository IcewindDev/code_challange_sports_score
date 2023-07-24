# code_challange_sports_score

Before we start...

#Assumptions and clarifications

    - Directories:
        - Models => holding data models
        - Resources => used for prepring any data needed for the code to run
        - Services => used for business logic
        - Test => used for unit tests
        - Helpers => any other logic needed that would not have a match any of the above
    # I am not sure if all will be needed after I finish the challange
    
    - External libaries
        - I will avoid using any external libraries which would do a better job at autoloading classes, data storing/mapping, writing unit test
    
    - I would assume that it would be better to use a language like node to handle any data real time using websockets, however, the current implementation will be in php
    - I assume this could be used as a part of a queue consumer (create/update/finish)

# IMPORTANT

    + Main logic located in Service\MatchHandler.php
    + Storage logic not implemented
    + Error logging not implemented (it would be best to create custom exceptions)

# Logic flow:
    + Match start => create new match and add teams after validation
    + Update score => can be optional if no goals are scored - the match entity is updated each time
    + Finish game => change status to FINISHED making it unavailable for update
    + Get Summary => this will get only the ongoing matches with their current score (the response should be paginated)