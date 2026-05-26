import '../../core/utils/logger.dart';

class OfflineDataValidator {
  bool validate(Map<String, dynamic> data, List<String> requiredFields) {
    for (final field in requiredFields) {
      if (!data.containsKey(field) || data[field] == null) {
        Logger.offline('Validation failed: missing $field');
        return false;
      }
    }
    return true;
  }

  bool validateAssessment(Map<String, dynamic> data) {
    return validate(data, ['id', 'title', 'status']);
  }

  bool validateIncident(Map<String, dynamic> data) {
    return validate(data, ['id', 'title', 'severity']);
  }
}