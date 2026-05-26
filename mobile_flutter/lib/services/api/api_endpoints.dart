class ApiEndpoints {
  static const String baseUrl = 'http://localhost:8000/api';
  static const String apiVersion = 'v1';
  
  static const String login = '/auth/login';
  static const String register = '/auth/register';
  static const String logout = '/auth/logout';
  static const String refreshToken = '/auth/refresh';
  static const String profile = '/user/profile';
  static const String assessments = '/assessments';
  static const String incidents = '/incidents';
  static const String vulnerabilities = '/vulnerabilities';
  static const String reports = '/reports';
  static const String keys = '/keys';
  static const String compliance = '/compliance';
  static const String sync = '/sync';
  static const String notifications = '/notifications';
  
  static String assessmentDetail(String id) => '$assessments/$id';
  static String incidentDetail(String id) => '$incidents/$id';
  static String vulnerabilityDetail(String id) => '$vulnerabilities/$id';
  static String reportDetail(String id) => '$reports/$id';
  static String keyDetail(String id) => '$keys/$id';
}