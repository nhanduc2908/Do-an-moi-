import '../models/report_model.dart';
import '../models/api_response_model.dart';
import '../datasources/remote/report_api.dart';
import '../../core/utils/logger.dart';

class ReportRepository {
  final ReportApi _reportApi;

  ReportRepository(this._reportApi);

  Future<ApiResponseModel<List<ReportModel>>> getReports({
    int page = 1,
    int limit = 20,
    String? type,
  }) async {
    try {
      return await _reportApi.getReports(page: page, limit: limit, type: type);
    } catch (e) {
      Logger.error('Get reports error', e);
      return ApiResponseModel(success: false, message: 'Failed to load reports');
    }
  }

  Future<ApiResponseModel<ReportModel>> getReportDetail(String id) async {
    try {
      return await _reportApi.getReportDetail(id);
    } catch (e) {
      Logger.error('Get report detail error', e);
      return ApiResponseModel(success: false, message: 'Failed to load report details');
    }
  }

  Future<ApiResponseModel<ReportModel>> generateReport(Map<String, dynamic> data) async {
    try {
      return await _reportApi.generateReport(data);
    } catch (e) {
      Logger.error('Generate report error', e);
      return ApiResponseModel(success: false, message: 'Failed to generate report');
    }
  }

  Future<ApiResponseModel<String>> exportReport(String id, String format) async {
    try {
      return await _reportApi.exportReport(id, format);
    } catch (e) {
      Logger.error('Export report error', e);
      return ApiResponseModel(success: false, message: 'Failed to export report');
    }
  }

  Future<ApiResponseModel<void>> shareReport(String id, List<String> recipients, String? message) async {
    try {
      return await _reportApi.shareReport(id, recipients, message);
    } catch (e) {
      Logger.error('Share report error', e);
      return ApiResponseModel(success: false, message: 'Failed to share report');
    }
  }

  Future<ApiResponseModel<void>> scheduleReport(Map<String, dynamic> schedule) async {
    try {
      return await _reportApi.scheduleReport(schedule);
    } catch (e) {
      Logger.error('Schedule report error', e);
      return ApiResponseModel(success: false, message: 'Failed to schedule report');
    }
  }

  Future<ApiResponseModel<void>> deleteReport(String id) async {
    try {
      return await _reportApi.deleteReport(id);
    } catch (e) {
      Logger.error('Delete report error', e);
      return ApiResponseModel(success: false, message: 'Failed to delete report');
    }
  }
}