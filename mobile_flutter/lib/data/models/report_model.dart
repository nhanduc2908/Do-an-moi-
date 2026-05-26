import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'report_model.freezed.dart';
part 'report_model.g.dart';

@freezed
class ReportModel with _$ReportModel {
  const factory ReportModel({
    String? id,
    @JsonKey(name: 'report_name') String? reportName,
    @JsonKey(name: 'report_type') String? reportType,
    dynamic filters,
    String? format,
    @JsonKey(name: 'file_path') String? filePath,
    @JsonKey(name: 'file_size') int? fileSize,
    @JsonKey(name: 'generated_by') String? generatedBy,
    @JsonKey(name: 'generated_at') DateTime? generatedAt,
    @JsonKey(name: 'expires_at') DateTime? expiresAt,
    @JsonKey(name: 'download_count') int? downloadCount,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _ReportModel;

  factory ReportModel.fromJson(Map<String, dynamic> json) => _$ReportModelFromJson(json);
}

extension ReportModelX on ReportModel {
  String get fileSizeDisplay {
    if (fileSize == null) return 'N/A';
    if (fileSize! < 1024) return '${fileSize} B';
    if (fileSize! < 1024 * 1024) return '${(fileSize! / 1024).toStringAsFixed(1)} KB';
    return '${(fileSize! / (1024 * 1024)).toStringAsFixed(1)} MB';
  }
  
  String get formatDisplay => format?.toUpperCase() ?? 'N/A';
  
  String get reportTypeDisplay {
    switch (reportType) {
      case 'security_summary': return 'Tổng quan bảo mật';
      case 'vulnerability_report': return 'Báo cáo lỗ hổng';
      case 'compliance_report': return 'Báo cáo tuân thủ';
      case 'incident_report': return 'Báo cáo sự cố';
      default: return reportType ?? 'Unknown';
    }
  }
}