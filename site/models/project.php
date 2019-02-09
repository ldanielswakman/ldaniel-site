<?php

class ProjectPage extends Page
{

	// Set default LIMIT.
	public function related (int $limit = 3)
	{
		// Let’s get all ELIGIBLE projects.
		$eligible_projects = $this->siblings(false);

	 	// Set RELATED & REMAINING to an empty Kirby\Pages collection.
		$related_projects = $remaining_projects = pages();

		// Get this project’s disciplines/tags and convert them to an array.
		$disciplines = $this->tags()->split();

		// Proceed if at least 1 discipline/tag has been set on this project.
		if (count($disciplines) > 0) {
			// Filter ELIGIBLE projects.
			$related_projects = $eligible_projects
				->filter(
					function ($project) use ($disciplines, $remaining_projects) {

						$project->weight = 0;

						// Cycle through all disciplines/tags and accumulate the result’s weight.
						foreach ($disciplines as $d) {
							if (strpos($project->tags(), $d) !== false) $project->weight++;
						}

						if ($project->weight > 0) {
							// Return (i.e. add to RELATED projects) if at least 1 result has been found.
							return true;
						} else {
							// If not, add this project to the REMAINING collection.
							$remaining_projects->add($project);
						}
					}
				)
				// Sort the resulting RELATED projects by weight.
				->sortBy('weight', 'desc');

			// Return if enough related projects have been found.
			if ($related_projects->count() > ($limit - 1)) {
				return $related_projects->limit($limit);
			}
		}

		// Find REMAINING projects where "title", "description" or "sections" match this project’s title. (boolean OR by default)
		$related_projects
			->add(
				$remaining_projects
					->search($this->title(), 'title|description|sections')
			);

		// Return if enough related projects have been found.
		if ($related_projects->count() > ($limit - 1)) {
			return $related_projects->limit($limit);
		}

		// Return at least the LIMIT, even when all 3 filter/search runs above produced no/not enough results.
		return $remaining_projects->shuffle()->limit($limit);
	}
}
