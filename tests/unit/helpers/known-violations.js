/**
 * Known contract-test findings, from tests/baselines/known-violations.json.
 *
 * A contract test compares what it finds in a file with what is listed here,
 * exactly. So a new finding fails, and a fixed finding that is still listed
 * fails too — the list can only shrink.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

import KNOWN from '../../baselines/known-violations.json';

/**
 * @param {string} check Check key, e.g. `per-install-ids`.
 * @param {string} file  Path relative to the theme root.
 * @return {string[]} Findings recorded for that file, or none.
 */
export function known( check, file ) {
	return KNOWN[ check ]?.[ file ] ?? [];
}
